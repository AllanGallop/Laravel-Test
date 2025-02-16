<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @OA\Get(
     *     path="/cart",
     *     summary="Get the user's cart",
     *     tags={"Cart"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cart retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="cart", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="total", type="number", format="float")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $cartItems = $this->cartService->getCart($userId);

        if(empty($cartItems)) {
            return response()->json($cartItems);
        }

        return response()->json(['message' => 'Cart is empty'], 404);

    }

    /**
     * @OA\Post(
     *     path="/cart",
     *     summary="Add or update a product in the cart",
     *     tags={"Cart"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "quantity"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product added/updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $userId = Auth::id();
        return response()->json(
            $this->cartService->addProduct($validated['product_id'], $validated['quantity'], $userId)
        );
    }

    /**
     * @OA\Delete(
     *     path="/cart",
     *     summary="Clear the authenticated user's cart",
     *     tags={"Cart"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cart cleared successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(): JsonResponse
    {
        $userId = Auth::id();
        return response()->json($this->cartService->clearCart($userId));
    }

    /**
     * @OA\Post(
     *     path="/checkout",
     *     summary="Checkout a user's cart and place an order",
     *     description="This endpoint allows a user to checkout their cart and create an order.",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Order successfully created.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="order_id", type="integer", example=123),
     *             @OA\Property(property="total_price", type="number", format="float", example=99.99),
     *             @OA\Property(property="message", type="string", example="Order placed successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Failed to process the checkout.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to process the checkout. Please try again.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. User must be authenticated.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
    public function checkout(): JsonResponse
    {
        $userId = Auth::id();
        $order = $this->cartService->checkout($userId);

        if($order['status']){
            return response()->json($order, 200);
        }
        
        return response()->json($order, 422);
    }
}
