<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     title="OrderItem",
 *     description="Represents an item within an order",
 *     type="object",
 *     required={"id", "order_id", "product_id", "quantity", "unit_price", "sub_total_price"},
 * 
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="order_id", type="integer", format="int64", description="ID of the associated order", example=101),
 *     @OA\Property(property="product_id", type="integer", format="int64", description="ID of the product in the order", example=50),
 *     @OA\Property(property="quantity", type="integer", description="Quantity of the product in the order", example=2),
 *     @OA\Property(property="unit_price", type="number", format="float", description="Price per unit of the product", example=19.99),
 *     @OA\Property(property="sub_total_price", type="number", format="float", description="Total price for this order item (unit_price * quantity)", example=39.98),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the order item was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the order item was last updated")
 * )
 */
class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';

    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'sub_total_price'];

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product of the cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get total price for this cart item.
     */
    public function getSubTotalPriceAttribute(): float
    {
        return $this->product->price * $this->quantity;
    }
}
