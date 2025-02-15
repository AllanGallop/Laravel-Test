<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function it_returns_single_product_by_id()
    {
        // Create a product
        $product = Product::factory()->create();

        // Fetch the product by its ID
        $retrievedProduct = $this->productService->getProductById($product->id);

        // Check that the returned product matches the created product
        $this->assertNotNull($retrievedProduct);
        $this->assertEquals($product->id, $retrievedProduct->id);
        $this->assertEquals($product->name, $retrievedProduct->name);
        $this->assertEquals($product->price, $retrievedProduct->price);
        $this->assertEquals($product->stock_quantity, $retrievedProduct->stock_quantity);
        $this->assertEquals($product->restock_quantity, $retrievedProduct->restock_quantity);
    }

    #[Test]
    public function it_returns_null_for_non_existent_product_id()
    {
        // Fetch a product by an ID that doesn't exist
        $retrievedProduct = $this->productService->getProductById(99999);

        // Assert that the returned product is null
        $this->assertNull($retrievedProduct);
    }
}
