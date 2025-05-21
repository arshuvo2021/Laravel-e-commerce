<?php

namespace Tests\Feature\Api\V2;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function it_can_list_products()
    {
        Product::factory()->count(3)->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/v2/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'count',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'category',
                        'in_stock',
                        'description'
                    ]
                ],
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page'
                ]
            ]);
    }

    /** @test */
    public function it_can_filter_products_by_category()
    {
        Product::factory()->count(2)->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/v2/products?category_id=' . $this->category->id);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_can_export_products_to_csv()
    {
        Product::factory()->count(5)->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/api/v2/products/export/csv');

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/csv'));
        $response->assertHeader('Content-Disposition', 'attachment; filename="products.csv"');
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $productData = [
            'name' => 'Test Product',
            'price' => 99.99,
            'in_stock' => true,
            'stock' => 100,
            'category_id' => $this->category->id,
            'description' => 'Test description'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/v2/products', $productData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'name',
                    'price',
                    'in_stock',
                    'stock',
                    'category_id',
                    'description'
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'name' => $productData['name'],
            'price' => $productData['price']
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_product()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/v2/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'in_stock', 'stock', 'category_id']);
    }

    /** @test */
    public function it_can_show_a_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/v2/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'name',
                    'price',
                    'in_stock',
                    'category',
                    'description'
                ]
            ]);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'price' => 149.99
        ];

        $response = $this->actingAs($this->user)
            ->putJson('/api/v2/products/' . $product->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'name',
                    'price',
                    'in_stock',
                    'category',
                    'description'
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $updateData['name'],
            'price' => $updateData['price']
        ]);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson('/api/v2/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }
} 