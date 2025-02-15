<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCart(int $userId)
    {
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        $total = $cartItems->sum('total_price');

        return [
            'cart' => $cartItems,
            'total' => $total
        ];
    }

    public function addProduct(int $productId, int $quantity, int $userId)
    {

        if ($quantity < 0) {
            return ['message' => 'Invalid quantity'];
        }

        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            if ($quantity == 0) {
                $cartItem->delete();
                return ['message' => 'Product removed from cart'];
            }

            $cartItem->update(['quantity' => $quantity]);

            return ['message' => 'Cart updated'];
        } else {
            if ($quantity > 0) {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);

                return ['message' => 'Product added to cart'];
            }
        }

        return ['message' => 'Invalid action'];
    }

    public function clearCart(int $userId)
    {
        Cart::where('user_id', $userId)->delete();

        return ['message' => 'Cart cleared'];
    }


    public function checkout(int $userId)
    {
        return DB::transaction(function ($userId) {
            // Retrieve all cart items for the user
            $cartItems = Cart::where('user_id', $userId)->get();

            // If the cart is empty, return
            if ($cartItems->isEmpty()) {
                return [
                    'status' => false, 
                    'message' => 'Cart is empty.'
                ];
            }

            // Check product availability
            $unavailableProducts = [];
            $totalPrice = 0;

            foreach ($cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);

                if (!$product || $product->stock_quantity < $cartItem->quantity) {
                    $unavailableProducts[] = [
                        'product_id' => $cartItem->product_id,
                        'available_stock' => $product ? $product->stock_quantity : 0
                    ];
                } else {
                    $totalPrice += $cartItem->quantity * $product->price;
                }
            }

            // If any products are unavailable, return a response
            if (!empty($unavailableProducts)) {
                return [
                    'status'  => false,
                    'message' => 'Some products are no longer available.',
                    'unavailable_products' => $unavailableProducts
                ];
            }

            // Create an order
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $totalPrice
            ]);

            // Create order items and update stock levels
            foreach ($cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);

                // Create order item entry
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $product->price,
                    'sub_total_price' => $cartItem->quantity * $product->price
                ]);

                // Deduct stock level
                $product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Clear the user's cart
            Cart::where('user_id', $userId)->delete();

            return [
                'status'  => true,
                'message' => 'Checkout successful',
                'order_id'=> $order->id
            ];
        });
    }
}