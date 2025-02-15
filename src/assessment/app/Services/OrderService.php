<?php
namespace App\Services;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    /**
     * Get a paginated list of orders
     * @param int $userId - The id of the user
     * @param int $perPage - Maximum items per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOrders(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Order::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get a distinct order, complete with product information
     * @param int $userId - The id of the user
     * @param int $id - Order ID
     * @return \App\Models\Order | null
     */
    public function getOrderById(int $userId, int $orderId): Order | null
    {
        return Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->with(['orderItems.product'])
            ->firstOrFail();
    }
}
