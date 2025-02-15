<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['user_id', 'total_price', 'status'];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the order items for this order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
