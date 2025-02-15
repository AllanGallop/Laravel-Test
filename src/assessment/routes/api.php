<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
        Route::get('/cart', [CartController::class, 'index']);       // View cart
        Route::post('/cart', [CartController::class, 'store']);      // Add/update product in cart
        Route::delete('/cart', [CartController::class, 'destroy']);  // Delete the cart
    }
);