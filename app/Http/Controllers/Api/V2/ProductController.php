<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::with('category')->select('id', 'name', 'price', 'in_stock', 'category_id', 'description');
        $this->applyFilters($query);
        $products = $query->paginate(15);

        return response()->json([
            'status' => 'success',
            'count' => $products->total(),
            'data' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'category' => optional($product->category)->name,
                    'in_stock' => (bool)$product->in_stock,
                    'description' => $product->description,
                ];
            }),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
            ],
        ]);
    }

    private function applyFilters($query)
    {
        if (request()->filled('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        if (request()->filled('min_price')) {
            $query->where('price', '>=', request('min_price'));
        }

        if ($sort = request('sort')) {
            if (in_array($sort, ['price', 'name'])) {
                $query->orderBy($sort);
            }
        }
    }

    public function exportCsv()
    {
        $query = Product::with('category')->select('id', 'name', 'price', 'in_stock', 'category_id', 'description');
        $this->applyFilters($query);
        $products = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Price', 'Category', 'In Stock', 'Description']);

            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->id,
                    $product->name,
                    $product->price,
                    optional($product->category)->name,
                    $product->in_stock ? 'Yes' : 'No',
                    $product->description,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $query = Product::with('category')->select('id', 'name', 'price', 'in_stock', 'category_id', 'description');
        $this->applyFilters($query);
        $products = $query->get();

        $pdf = Pdf::loadView('products.pdf', compact('products'));

        return $pdf->download('products.pdf');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'in_stock' => 'required|boolean',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json([
            'status' => 'success', // Changed from 'success' => true for consistency
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'in_stock' => (bool)$product->in_stock,
                'category' => optional($product->category)->name,
                'description' => $product->description,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'in_stock' => 'sometimes|boolean',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $product->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'status' => 'success', // Changed for consistency
            'message' => 'Product deleted successfully',
        ]);
    }
}