<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * List all orders for the authenticated user.
     *
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get list of orders",
     *     tags={"Orders"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="List of orders retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=3),
     *                     @OA\Property(property="total_price", type="number", format="float", example=120.50),
     *                     @OA\Property(property="status", type="string", example="completed"),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getOrders($request->user()->id);
        return response()->json($orders);
    }

    /**
     * Get details of a specific order.
     *
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     summary="Get order details",
     *     tags={"Orders"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=3),
     *             @OA\Property(property="total_price", type="number", format="float", example=120.50),
     *             @OA\Property(property="status", type="string", example="completed"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="order_items", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=5),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="unit_price", type="number", format="float", example=30.25),
     *                     @OA\Property(property="sub_total_price", type="number", format="float", example=60.50)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show(Request $request, $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id, $request->user()->id);
        return response()->json($order);
    }
}
