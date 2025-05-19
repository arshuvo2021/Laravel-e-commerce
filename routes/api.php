<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'indexV1']);
Route::get('/v2/products', [ProductController::class, 'indexV2']);
Route::post('/products', [ProductController::class, 'store']);
