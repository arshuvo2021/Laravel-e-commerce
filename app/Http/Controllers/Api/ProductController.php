<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function indexV1()
    {
        return Product::all(); // Exposes internal IDs and timestamps
    }

    public function indexV2()
    {
        return Product::all()->map(function ($product) {
            return [
                'product_name' => $product->name, // Changed key
                'price' => $product->price,
                'in_stock' => $product->stock > 0 // New field
            ];
        });
    }

    public function store(Request $request)
    {
        return Product::create($request->all());
    }
}
