<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $cartService;

    public function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->cartService = new CartService();
    }

    public function test_checkout_process()
    {
        $this->actingAs($this->user);

        // Create products and add them to the cart
        $product1 = Product::factory()->create([
            'stock_level' => 10,
            'price' => 100
        ]);
        
        $product2 = Product::factory()->create([
            'stock_level' => 5,
            'price' => 50
        ]);

        // Add products to the cart for the user
        $this->postJson('/api/cart', [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);
        
        $this->postJson('/api/cart', [
            'product_id' => $product2->id,
            'quantity' => 1
        ]);

        // Check the cart has been updated in the database
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product1->id,
            'quantity' => 2
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product2->id,
            'quantity' => 1
        ]);

        // Checkout the cart
        $this->postJson('/api/cart/checkout')
            ->assertStatus(200);

        // Verify that an order is created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
        ]);

        // Retrieve the created order
        $order = Order::where('user_id', $this->user->id)->first();

        // Verify that order_items have been created for the products
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'unit_price' => $product1->price,
            'sub_total_price' => $product1->price * 2
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'unit_price' => $product2->price,
            'sub_total_price' => $product2->price * 1
        ]);
        
        // Ensure stock levels are decremented (if stock management is implemented)
        $product1->refresh();
        $product2->refresh();
        
        $this->assertEquals(8, $product1->stock_level);
        $this->assertEquals(4, $product2->stock_level);
    }

}
