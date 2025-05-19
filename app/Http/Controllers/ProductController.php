<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products with pagination
    public function index()
    {
        $products = Product::with('category')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    // Show create product form
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Store a new product
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'in_stock'    => 'sometimes|boolean',
        ]);

        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'category_id' => $request->category_id,
            'in_stock'    => $request->has('in_stock'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    // Show edit form for a product
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'in_stock'    => 'sometimes|boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'category_id' => $request->category_id,
            'in_stock'    => $request->has('in_stock'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // Search products by name or category name
    public function search(Request $request)
    {
        $query = $request->input('query');
        $expensiveOnly = $request->has('expensive_only');
    
        $products = Product::with('category')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($q2) use ($query) {
                      $q2->where('name', 'like', "%{$query}%");
                  });
            });
    
        if ($expensiveOnly) {
            $products = $products->expensive(); // scopeExpensive
        }
    
        $products = $products->paginate(20);
    
        return view('product.index', compact('products'));
    }

    // Update stock status of a product (via POST)
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'in_stock' => 'required|boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->in_stock = $request->in_stock;
        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Stock updated successfully.');
    }

    
}
