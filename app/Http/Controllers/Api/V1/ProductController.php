<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List products with pagination, eager loading, and limited exposed fields
    public function index()
    {
        $products = Product::with('category')
            ->select('id', 'name', 'price', 'in_stock', 'category_id') // Explicit fields only
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    // Store new product with validation and safe data handling
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'in_stock' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'data' => $product,
        ], 201);
    }
}
