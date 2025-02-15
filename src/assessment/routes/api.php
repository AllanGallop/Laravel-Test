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
        Route::get('/', 'index');       // View cart
        Route::post('/', 'store');      // Add/update product in cart
        Route::delete('/', 'destroy');  // Delete the cart
    }
);