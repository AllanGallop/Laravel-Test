<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
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
}
