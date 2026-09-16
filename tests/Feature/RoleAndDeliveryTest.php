<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAndDeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_panel_is_forbidden_for_customers(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get('/admin/orders')->assertForbidden();
    }

    public function test_admin_panel_is_forbidden_for_couriers(): void
    {
        $courier = User::factory()->courier()->create();

        $this->actingAs($courier)->get('/admin/orders')->assertForbidden();
    }

    public function test_courier_panel_is_forbidden_for_customers(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get('/courier/deliveries')->assertForbidden();
    }

    public function test_admin_can_access_panels(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/orders')->assertOk();
        $this->actingAs($admin)->get('/admin/customers')->assertOk();
    }

    public function test_courier_advances_delivery_and_completes_payment(): void
    {
        $courier = User::factory()->courier()->create();
        $customer = User::factory()->create();
        $product = $this->makeProduct();
        $order = Order::create([
            'user_id' => $customer->id,
            'courier_id' => $courier->id,
            'status' => 'placed',
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
        $order->payments()->create([
            'method' => 'cash',
            'amount' => 25.00,
            'status' => 'pending',
        ]);

        $this->actingAs($courier)->get(route('courier.deliveries.index'))
            ->assertOk()
            ->assertSee('Delivery #'.$order->id);

        $this->actingAs($courier)->patch(route('courier.deliveries.status', $order), [
            'status' => 'out_for_delivery',
            'location' => 'Downtown',
        ])->assertRedirect();

        $this->assertSame('out_for_delivery', $order->fresh()->status);
        $this->assertDatabaseHas('order_tracking_events', [
            'order_id' => $order->id,
            'status' => 'out_for_delivery',
            'location' => 'Downtown',
        ]);

        $this->actingAs($courier)->patch(route('courier.deliveries.status', $order), [
            'status' => 'delivered',
        ])->assertRedirect();

        $order->refresh();
        $this->assertSame('delivered', $order->status);
        $this->assertNotNull($order->delivered_at);
        $this->assertSame('completed', $order->payments()->first()->status);

        $this->actingAs($customer)->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('Delivered');
    }

    public function test_customer_can_submit_and_update_a_review(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($user)->post(route('reviews.store'), [
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Lovely bouquet!',
        ])->assertRedirect();

        $this->assertDatabaseCount('reviews', 1);

        $this->actingAs($user)->post(route('reviews.store'), [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Actually even better.',
        ])->assertRedirect();

        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseHas('reviews', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Actually even better.',
        ]);

        $this->actingAs($user)->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Actually even better.');
    }

    private function makeProduct(): Product
    {
        $category = Category::create(['name' => 'Bouquets']);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 25.00,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);
    }
}
