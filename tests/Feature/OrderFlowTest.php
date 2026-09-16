<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
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

    public function test_guests_cannot_place_an_order(): void
    {
        $product = $this->makeProduct();

        $response = $this->post('/api/orders', [
            'recipient_name' => 'Jane Doe',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ])->assertStatus(401);

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_authenticated_user_can_place_an_order(): void
    {
        $user = User::factory()->create(['phone' => '+256700000000']);
        $product = $this->makeProduct(stock: 5);

        $response = $this->actingAs($user)->postJson('/api/orders', [
            'recipient_name' => 'Jane Doe',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
            'payment_method' => 'mobile_money',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(201);

        $order = Order::firstOrFail();
        $this->assertSame($user->id, $order->user_id);
        $this->assertSame('50.00', (string) $order->total_amount);
        $this->assertSame(3, $product->fresh()->stock_quantity);
        $this->assertSame(1, $order->items()->count());
        $this->assertSame('mobile_money', $order->payments()->first()->method);
    }

    public function test_insufficient_stock_rejects_the_order(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct(stock: 1);

        $this->actingAs($user)->postJson('/api/orders', [
            'recipient_name' => 'Jane Doe',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ])->assertStatus(422);

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $product->fresh()->stock_quantity);
    }
}
