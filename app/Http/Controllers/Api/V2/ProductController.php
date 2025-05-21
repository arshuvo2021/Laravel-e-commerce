<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * @OA\Info(
 *     version="2.0.0",
 *     title="E-Commerce API V2",
 *     description="API documentation for the E-Commerce system",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/products",
     *     summary="Get list of products",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="Filter by minimum price",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort by field (price or name)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="count", type="integer", example=100),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="price", type="number"),
     *                     @OA\Property(property="category", type="string"),
     *                     @OA\Property(property="in_stock", type="boolean"),
     *                     @OA\Property(property="description", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($handle, ['ID', 'Name', 'Price', 'Category', 'In Stock', 'Description']);

            // Apply any filters from the request
            $query = Product::with('category')
                ->select('id', 'name', 'price', 'in_stock', 'category_id', 'description');
            $this->applyFilters($query);

            // Process in chunks to manage memory
            $query->chunk(1000, function ($products) use ($handle) {
                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->id,
                        $product->name,
                        number_format($product->price, 2),
                        optional($product->category)->name,
                        $product->in_stock ? 'Yes' : 'No',
                        $product->description,
                    ]);
                }
                
                // Flush the output buffer to prevent memory issues
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            });

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

    /**
     * @OA\Post(
     *     path="/api/v2/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","in_stock","stock","category_id"},
     *             @OA\Property(property="name", type="string", example="Product Name"),
     *             @OA\Property(property="price", type="number", example=99.99),
     *             @OA\Property(property="in_stock", type="boolean", example=true),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="Product description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(property="in_stock", type="boolean"),
     *                 @OA\Property(property="stock", type="integer"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="description", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
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

        // Ensure stock is set to 0 if not provided
        if (!isset($validated['stock'])) {
            $validated['stock'] = 0;
        }

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

        // Update in_stock based on stock value if stock is being updated
        if (isset($validated['stock'])) {
            $validated['in_stock'] = $validated['stock'] > 0;
        }

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
            'status' => 'success',
            'message' => 'Product deleted successfully',
        ]);
    }
}