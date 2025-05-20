<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController; // ← You missed importing this!

// Public route
Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Auth::routes();

// All admin routes protected
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('products/{id}/stock', [ProductController::class, 'updateStock'])->name('products.stock');
});

// Dashboard & Home, both protected
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
