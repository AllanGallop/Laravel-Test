<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_price' => 0,
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the factory to create associated order items.
     */
    public function withItems(int $count = 3)
    {
        return $this->afterCreating(function (Order $order) use ($count) {
            $orderItems = \App\Models\OrderItem::factory($count)->create(['order_id' => $order->id]);

            // Recalculate total price based on order items
            $order->total_price = $orderItems->sum('sub_total_price');
            $order->save();
        });
    }
}
