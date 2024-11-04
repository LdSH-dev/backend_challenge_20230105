<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\ApiKeyMiddleware;

Route::middleware(ApiKeyMiddleware::class)->group(function () {
    Route::get('/', [ApiController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{code}', [ProductController::class, 'show']);
    Route::put('/products/{code}', [ProductController::class, 'update']);
    Route::delete('/products/{code}', [ProductController::class, 'destroy']);
    Route::post('/product', [ProductController::class, 'create']);
});
