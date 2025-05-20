<?php

namespace Tests\Feature\Api\V2;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
    }

    public function test_it_can_list_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'count',
                     'data' => [
                         '*' => ['id', 'name', 'price', 'in_stock', 'category', 'description']
                     ],
                     'pagination' => ['current_page', 'last_page', 'per_page'],
                 ]);
    }

    public function test_it_can_export_products_to_csv()
    {
        Product::factory()->count(5)->create();

        $response = $this->get('/api/v2/products/export/csv');

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_it_can_export_products_to_pdf()
    {
        Product::factory()->count(5)->create();

        $response = $this->get('/api/v2/products/export/pdf');

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_it_validates_required_fields_when_creating_product()
    {
        $response = $this->postJson('/api/v2/products', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'in_stock', 'category_id']);
    }

    public function test_it_can_create_a_product()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Test Product',
            'price' => 99.99,
            'in_stock' => true,
            'stock' => 10,
            'category_id' => $category->id,
            'description' => 'Test Description'
        ];

        $response = $this->postJson('/api/v2/products', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                         'name' => 'Test Product',
                         'price' => 99.99,
                         'in_stock' => true,
                         'stock' => 10,
                         'category_id' => $category->id,
                         'description' => 'Test Description'
                     ]
                 ]);
    }
} 