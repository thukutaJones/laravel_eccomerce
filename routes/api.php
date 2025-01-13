<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('user', UserController::class);
Route::apiResource('auth', AuthController::class);
Route::apiResource('category', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::get('products/get-merchant-products/{id}', [ProductController::class, 'getProductsByUserId']);
Route::apiResource('orders', OrderController::class);
Route::get('orders/get-customer-cart/{id}', [OrderController::class, 'getCartByUserId']);
Route::get('orders/get-orders-by-product-user/{userId}', [OrderController::class, 'getOrdersByProductUserId']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
