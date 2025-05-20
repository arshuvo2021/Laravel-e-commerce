<?php

namespace Tests\Feature\Api\V1;

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

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'data' => [
                             '*' => ['id', 'name', 'price', 'in_stock', 'category_id']
                         ],
                         'current_page',
                         'last_page',
                         'per_page',
                         'total'
                     ]
                 ]);
    }

    public function test_it_can_create_a_product()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Test Product',
            'price' => 99.99,
            'in_stock' => true,
            'category_id' => $category->id,
            'stock' => 10
        ];

        $response = $this->postJson('/api/v1/products', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'name' => 'Test Product',
                         'price' => 99.99,
                         'in_stock' => true,
                         'category_id' => $category->id
                     ]
                 ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99,
            'in_stock' => true,
            'category_id' => $category->id
        ]);
    }

    public function test_it_validates_required_fields_when_creating_product()
    {
        $response = $this->postJson('/api/v1/products', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'in_stock', 'category_id']);
    }
} 