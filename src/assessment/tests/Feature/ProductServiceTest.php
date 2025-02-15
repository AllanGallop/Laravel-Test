<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;

    public function setUp():void
    {
        parent::setUp();
        $this->productService = new ProductService();
    }

    #[Test]
    public function it_returns_paginated_products()
    {
        // Create 50 products
        Product::factory()->count(50)->create();

        // Request the first page with 10 products per page
        $response = $this->getJson('/api/products?per_page=10');

        // Check that the response
        $response->assertStatus(200)
                 // Check that the response is paginated
                 ->assertJsonStructure([
                     'current_page', 'data', 'first_page_url', 'last_page_url', 
                     'next_page_url', 'prev_page_url', 'to', 'total', 'per_page'
                 ])
                 // Check that that there are 10 products on the page
                 ->assertJsonCount(10, 'data');  
    }

    #[Test]
    public function it_returns_product_by_id()
    {
        // Create a product
        $product = Product::factory()->create();

        // Request the product
        $response = $this->getJson("/api/products/{$product->id}");

        // Check that the product is returned correctly
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $product->id,
                     'name' => $product->name,
                     'price' => $product->price,
                     'stock_quantity' => $product->stock_quantity,
                     'restock_quantity' => $product->restock_quantity,
                 ]);
    }

    #[Test]
    public function it_returns_404_for_non_existent_product()
    {
        // Fetch a product that doesn't exist
        $response = $this->getJson('/api/products/99999');

        // Assert the response is 404
        $response->assertStatus(404);
    }

}
