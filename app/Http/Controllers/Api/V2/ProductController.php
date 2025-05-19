<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Paginate to improve performance on large datasets
        $products = Product::with('category')
            ->select('id', 'name', 'price', 'in_stock', 'category_id')
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'count' => $products->total(),
            'data' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'category' => optional($product->category)->name,
                    'in_stock' => $product->in_stock > 0,  // fix attribute name
                ];
            }),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
            ],
        ]);
    }
}
