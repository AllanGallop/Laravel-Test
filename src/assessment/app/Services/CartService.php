<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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

    public function addProductToCart(int $productId, int $quantity, int $userId)
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
}