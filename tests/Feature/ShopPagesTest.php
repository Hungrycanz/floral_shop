<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_lists_active_products(): void
    {
        $category = Category::create(['name' => 'Bouquets']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Rose Bouquet',
            'price' => 25.00,
            'stock_quantity' => 5,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Rose Bouquet');
    }

    public function test_landing_page_hides_inactive_products(): void
    {
        $category = Category::create(['name' => 'Bouquets']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Gone Bouquet',
            'price' => 25.00,
            'stock_quantity' => 5,
            'is_active' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Gone Bouquet');
    }

    public function test_product_page_shows_signup_gate_for_guests(): void
    {
        $category = Category::create(['name' => 'Bouquets']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Rose Bouquet',
            'price' => 25.00,
            'stock_quantity' => 5,
            'is_active' => true,
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Sign up')
            ->assertSee('Log in');
    }

    public function test_authenticated_user_can_view_their_order(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'recipient_name' => 'Jane',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDay(),
            'total_amount' => 25.00,
        ]);

        $this->actingAs($user)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('Order #'.$order->id);
    }

    public function test_user_cannot_view_someone_elses_order(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $order = Order::create([
            'user_id' => $owner->id,
            'status' => 'pending',
            'recipient_name' => 'Jane',
            'recipient_phone' => '+256700000000',
            'delivery_address' => '1 Flower Street',
            'delivery_date' => now()->addDay(),
            'total_amount' => 25.00,
        ]);

        $this->actingAs($intruder)
            ->get(route('orders.show', $order))
            ->assertForbidden();
    }
}
