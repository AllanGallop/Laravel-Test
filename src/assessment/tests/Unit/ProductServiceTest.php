<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;

    /**
     * Setup 
     */
    public function setUp():void
    {
        parent::setUp();
        $this->productService = new ProductService();
    }

    #[Test]
    public function it_returns_products()
    {
        // Create 50 products
        Product::factory()->count(50)->create();

        // Get the first page with 10 products per page
        $products = $this->productService->getProducts(10);

        // Check that the pagination works as expected
        $this->assertEquals(10, $products->count());  // First page should have 10 products
        $this->assertEquals(50, $products->total());  // Total should be 50 products
    }
}
