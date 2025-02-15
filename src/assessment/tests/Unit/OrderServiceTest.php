<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_list_order_details_with_order_items()
    {
        // Create a user
        $user = User::factory()->create();
        // Create an order
        $order = Order::factory()->create(['user_id' => $user->id]);
        // Create some products
        $products = Product::factory()->count(3)->create();
        // Map products to the order items
        $orderItems = $products->map(function ($product) use ($order) {
            return OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => rand(1, 3),
                'unit_price' => $product->price,
                'sub_total_price' => fn ($attrs) => $attrs['quantity'] * $attrs['unit_price'],
            ]);
        });

        // Get the order with its items
        $retrievedOrder = Order::with('orderItems')->find($order->id);

        // Check if order details match
        $this->assertNotNull($retrievedOrder);
        $this->assertEquals($user->id, $retrievedOrder->user_id);
        $this->assertEquals($order->id, $retrievedOrder->id);
        $this->assertCount(3, $retrievedOrder->orderItems);

        // Check if each order item matches the created ones
        foreach ($retrievedOrder->orderItems as $index => $retrievedItem) {
            $this->assertEquals($orderItems[$index]->product_id, $retrievedItem->product_id);
            $this->assertEquals($orderItems[$index]->quantity, $retrievedItem->quantity);
            $this->assertEquals($orderItems[$index]->unit_price, $retrievedItem->unit_price);
            $this->assertEquals($orderItems[$index]->sub_total_price, $retrievedItem->sub_total_price);
        }

        // Check total price calculation
        $expectedTotal = $retrievedOrder->orderItems->sum('sub_total_price');
        $this->assertEquals($expectedTotal, $retrievedOrder->total_price);
    }
}
