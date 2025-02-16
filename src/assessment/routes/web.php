<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(AdminMiddleware::class)->group(function () {
    Route::view('/admin/orders', 'admin.orders')->name('admin.orders.index');
    Route::view('/admin/products', 'admin.dashboard')->name('admin.products.index');
});
