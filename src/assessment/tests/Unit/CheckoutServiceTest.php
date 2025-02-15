<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\User;
use App\Services\CartService;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;
    protected $cartService;

    /**
     * Setup 
     */
    public function setUp():void
    {
        parent::setUp();
        $this->productService = new ProductService();
        $this->cartService = new CartService();
    }

    #[Test]
    public function checkout_cart()
    {
        // Create a product
        $product = Product::factory()->create();

        // Create a user
        $user = User::factory()->create();

        // Add product to cart
        $this->cartService->addProduct($product->id, 1, $user->id);

        // Assert product is in the cart
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        // Checkout the cart
        $this->cartService->checkout($user->id);

        // Assert order is created
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'qty' => 1
        ]);
    }

}