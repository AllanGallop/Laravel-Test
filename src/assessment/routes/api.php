<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        return response()->json([
            'token' => $user->createToken('MyApp')->plainTextToken,
        ]);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
});

// Products
Route::controller(ProductController::class)
    ->prefix('/products')
    ->group(function(){
        Route::get('/', 'list');
        Route::get('/{id}', 'get');
    }
);

// Cart
Route::controller(CartController::class)
    ->prefix('/cart')
    ->middleware('auth:sanctum')
    ->group(function(){
        Route::get('/', 'index');       // View cart
        Route::post('/', 'store');      // Add/update product in cart
        Route::delete('/', 'destroy');  // Delete the cart
        Route::post('/checkout', 'checkout'); // Checkout the cart
    }
);

// Orders
Route::controller( OrderController::class)
    ->prefix('/orders')
    ->middleware('auth:sanctum')
    ->group(function(){
        Route::get('/', 'index');       // View Orders
        Route::get('/{id}', 'show');    // View order by by
    }
);