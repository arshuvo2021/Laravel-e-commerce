<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);

    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/products/{id}/stock', [ProductController::class, 'updateStock'])->name('products.stock');
});



Route::get('/home', [HomeController::class, 'index'])->name('home');
