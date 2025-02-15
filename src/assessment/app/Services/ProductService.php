<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Get a paginated list of products
     * @param int $perPage - Maximum items per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getProducts(int $perPage = 15): LengthAwarePaginator
    {
        // Fetch products excluded deleted and paginate
        return Product::whereNull('deleted_at')->paginate($perPage);
    }

    /**
     * Get a distinct product
     * @param int $id - Product ID
     * @return \App\Models\Product | null
     */
    public function getProductById(int $id): Product | null
    {
        // Fetch a product by ID
        return Product::find($id);
    }
}
