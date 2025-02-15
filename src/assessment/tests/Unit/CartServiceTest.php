<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $cartService;

    /**
     * Setup 
     */
    public function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->cartService = new CartService();
    }

    #[Test]
    public function it_can_add_products_to_cart()
    {
        // Create a product
        $product = Product::factory()->create();

        // Add Product to cart
        $this->cartService->addProduct($product->id, 2, $this->user->id);

        // Check exists in database
        $this->assertDatabaseHas('carts', [
            'user_id'    => $this->user->id,
            'product_id' => $product->id,
            'quantity'   => 2
        ]);
    }

    #[Test]
    public function it_updates_quantity_if_already_exists()
    {
        // Create a product
        $product = Product::factory()->create();

        // Add products to cart
        $this->cartService->addProductToCart($product->id, 2, $this->user->id);

        // Check database for entry
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Add the same product again
        $this->cartService->addProductToCart($product->id, 3, $this->user->id);

        // Check updated quantity
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 3
        ]);
    }

    #[Test]
    public function it_removes_product_from_cart_if_quantity_is_set_to_zero()
    {
        $this->actingAs($this->user);

        $product = Product::factory()->create();
        
        // Add product to cart
        $cartItem = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Remove from cart
        $this->cartService->addProductToCart($product->id, 0, $this->user->id);

        // Assert the product was removed
        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
    }


}