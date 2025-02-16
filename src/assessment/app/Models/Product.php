<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model",
 *     type="object",
 *     required={"id", "name", "price", "stock_level"},
 * 
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Product Name"),
 *     @OA\Property(property="description", type="string", example="Product Description"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="stock_level", type="integer", example=10),
 *     @OA\Property(property="reorder_stock_level", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = ['name', 'price', 'stock_quantity', 'restock_quantity'];

    protected $dates = ['deleted_at'];

}
