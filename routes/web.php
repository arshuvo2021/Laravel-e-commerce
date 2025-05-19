<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/{id}/stock', [ProductController::class, 'updateStock']);
Route::get('/search', [ProductController::class, 'search']);
