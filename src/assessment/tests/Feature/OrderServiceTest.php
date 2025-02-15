<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    public function setUp():void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    #[Test]
    public function it_returns_paginated_products()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Authenticate as user
        $this->actingAs($user);

        // Create 5 Orders
        Order::factory()->count(5)->create();

        // Request the first page with 10 products per page
        $response = $this->getJson('/api/orders?per_page=10');

        // Check that the response
        $response->assertStatus(200)
                 // Check that the response is paginated
                 ->assertJsonStructure([
                     'current_page', 'data', 'first_page_url', 'last_page_url', 
                     'next_page_url', 'prev_page_url', 'to', 'total', 'per_page'
                 ])
                 // Check that that there are 10 products on the page
                 ->assertJsonCount(10, 'data');  
    }

    #[Test]
    public function it_returns_order_by_id()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate as user
        $this->actingAs($user);

        // Create an order for the user
        $order = Order::factory()->create(['user_id' => $user->id]);

        // Create products and order items
        $products = Product::factory()->count(3)->create();

        $orderItems = $products->map(function ($product) use ($order) {
            return OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => rand(1, 3),
                'unit_price' => $product->price,
                'sub_total_price' => fn ($attrs) => $attrs['quantity'] * $attrs['unit_price'],
            ]);
        });

        // Act: Send a request to get the order details
        $response = $this->getJson(route('orders.show', ['order' => $order->id]));

        // Assert: Check if the response is successful
        $response->assertStatus(200);

        // Assert: Check if order details are returned correctly
        $response->assertJson([
            'id' => $order->id,
            'user_id' => $user->id,
            'total_price' => $order->orderItems->sum('sub_total_price'),
            'status' => $order->status,
            'order_items' => $order->orderItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'sub_total_price' => $item->sub_total_price,
                ];
            })->toArray(),
        ]);
    }

    #[Test]
    public function it_returns_404_for_non_existent_orders()
    {
        // Fetch a product that doesn't exist
        $response = $this->getJson('/api/orders/99999');

        // Assert the response is 404
        $response->assertStatus(404);
    }
}
