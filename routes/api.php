<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController as ProductV1;
use App\Http\Controllers\Api\V2\ProductController as ProductV2;
use App\Http\Controllers\Api\V3\ProductController as ProductV3;

// Apply rate limiting and authentication to all API routes
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::prefix('v1')->name('api.v1.')->group(function () {
        Route::get('products', [ProductV1::class, 'index'])->name('products.index');
        Route::post('products', [ProductV1::class, 'store'])->name('products.store');
    });

    Route::prefix('v2')->name('api.v2.')->group(function () {
        Route::apiResource('products', ProductV2::class)->only([
            'index', 'store', 'show', 'update', 'destroy'
        ]);
        
        Route::get('products/export/csv', [ProductV2::class, 'exportCsv'])->name('products.export.csv');
        Route::get('products/export/pdf', [ProductV2::class, 'exportPdf'])->name('products.export.pdf');
    });

    Route::prefix('v3')->name('api.v3.')->group(function () {
        Route::apiResource('products', ProductV3::class); 
    });
});
