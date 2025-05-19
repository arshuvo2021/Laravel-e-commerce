<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('products.index', [
            'products' => $products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category->name
                ];
            })
        ]);
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::find($id);
        $product->stock -= $request->quantity; // Unsafe
        $product->save();

        return response()->json($product);
    }

    public function search(Request $request)
    {
        $results = DB::select(
            "SELECT * FROM products WHERE name LIKE '%".$request->input('query')."%'"
        );

        return response()->json($results);
    }
}
