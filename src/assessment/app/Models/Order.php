<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Order",
 *     title="Order",
 *     description="Order model",
 *     type="object",
 *     required={"id", "user_id", "total_price", "status"},
 * 
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who placed the order", example=2),
 *     @OA\Property(property="total_price", type="number", format="float", description="Total price of the order", example=150.75),
 *     @OA\Property(property="status", type="string", description="Order status", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the order was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the order was last updated"),
 * 
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User",
 *         description="User who placed the order"
 *     ),
 * 
 *     @OA\Property(
 *         property="order_items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OrderItem"),
 *         description="List of items in the order"
 *     )
 * )
 */

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = ['user_id', 'total_price', 'status'];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for this order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
