<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $cartService;

    public function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->cartService = new CartService();
    }

    /** @test */
    public function it_adds_product_to_cart_or_updates_quantity()
    {
        $this->actingAs($this->user);

        $product = Product::factory()->create(['price' => 100]);

        // Add product to cart
        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 2
        ])->assertStatus(200)
        ->assertJson(['message' => 'Product added to cart']);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Add the same product again
        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 3
        ])->assertStatus(200)
        ->assertJson(['message' => 'Cart updated']);

        // Check that the quantity was updated instead of creating a duplicate row
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 3
        ]);
    }

    #[Test]
    public function it_returns_404_for_empty_cart()
    {
        $this->actingAs($this->user);

        // Delete the cart
        $this->deleteJson('/api/cart')->assertStatus(200)
        ->assertJson(['message' => 'Cart cleared']);

        // Fetch a product that doesn't exist
        $response = $this->getJson('/api/cart');

        // Assert the response is 404
        $response->assertStatus(404);
    }



}
