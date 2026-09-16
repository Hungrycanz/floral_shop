<?php

namespace Tests\Feature;

use App\Models\BouquetOption;
use App\Models\Category;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(int $stock = 5): Product
    {
        $category = Category::create(['name' => 'Bouquets']);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Test Bouquet',
            'price' => 25.00,
            'stock_quantity' => $stock,
            'is_active' => true,
        ]);
    }

    public function test_cart_flow_with_addons_and_checkout(): void
    {
        $user = User::factory()->create(['phone' => '+256700000000']);
        $product = $this->makeProduct();
        $addon = BouquetOption::create(['name' => 'Chocolates box', 'price' => 12.00]);
        $zone = DeliveryZone::create(['name' => 'City Centre', 'price' => 5.00]);

        $this->actingAs($user)->post('/cart', [
            'product_id' => $product->id,
            'quantity' => 2,
            'addon_ids' => [$addon->id],
        ])->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Test Bouquet')
            ->assertSee('Chocolates box');

        $this->post(route('checkout.place'), [
            'recipient_name' => 'Jane Doe',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
            'payment_method' => 'cash',
            'delivery_zone_id' => $zone->id,
        ])->assertRedirect();

        $order = Order::firstOrFail();
        $this->assertSame($user->id, $order->user_id);
        $this->assertSame($zone->id, $order->delivery_zone_id);
        $this->assertSame('placed', $order->status);
        $this->assertEqualsWithDelta(79.00, (float) $order->total_amount, 0.001);
        $this->assertSame(1, $order->trackingEvents()->count());
        $this->assertSame('pending', $order->payments()->first()->status);
        $this->assertSame($addon->name, $order->items()->first()->addons[0]['name']);
        $this->assertSame(3, $product->fresh()->stock_quantity);

        $this->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('Order placed')
            ->assertSee('Tracking');
    }

    public function test_customers_can_reorder_a_delivered_order(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'delivered',
            'recipient_name' => 'Jane',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDay(),
            'total_amount' => 25.00,
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 25.00,
            'subtotal' => 25.00,
        ]);

        $this->actingAs($user)->post(route('orders.reorder', $order))
            ->assertRedirect(route('cart.index'));

        $this->assertSame(1, session('cart')[0]['quantity']);
        $this->assertSame($product->id, session('cart')[0]['product_id']);
    }

    public function test_customer_can_cancel_an_order_and_restores_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct(stock: 3);
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'placed',
            'recipient_name' => 'Jane',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDay(),
            'total_amount' => 25.00,
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 25.00,
            'subtotal' => 50.00,
        ]);
        $product->decrement('stock_quantity', 2);

        $this->actingAs($user)->post(route('orders.cancel', $order))
            ->assertRedirect();

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(3, $product->fresh()->stock_quantity);
        $this->assertDatabaseHas('order_tracking_events', [
            'order_id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_payment_can_be_processed_by_admin(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'placed',
            'recipient_name' => 'Jane',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDay(),
            'total_amount' => 25.00,
        ]);
        $payment = $order->payments()->create([
            'method' => 'card',
            'amount' => 25.00,
            'status' => 'pending',
        ]);

        $this->actingAs($user)->post(route('payments.process', $payment))
            ->assertForbidden();

        $this->assertSame('pending', $payment->fresh()->status);

        $this->actingAs($admin)->post(route('payments.process', $payment))
            ->assertRedirect();

        $this->assertSame('completed', $payment->fresh()->status);
    }
}
