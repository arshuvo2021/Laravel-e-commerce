<?php

namespace Tests\Feature\Api\V2;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

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
                 ])
                 ->assertJson([
                     'status' => 'success', // Changed from 'success' => true
                 ]);
    }

    public function test_it_can_show_a_single_product()
    {
        $product = Product::factory()->create(['in_stock' => true]);

        $response = $this->getJson("/api/v2/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success', // Changed from 'success' => true
                     'data' => [
                         'id' => $product->id,
                         'name' => $product->name,
                         'price' => $product->price,
                         'in_stock' => true,
                         'category' => $product->category->name ?? null,
                         'description' => $product->description,
                     ],
                 ]);
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

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99,
            'in_stock' => true,
            'stock' => 10,
            'category_id' => $category->id,
            'description' => 'Test Description'
        ]);
    }

    public function test_it_can_update_a_product()
    {
        $product = Product::factory()->create(['name' => 'Old Name']);
        $category = Category::factory()->create();

        $response = $this->putJson("/api/v2/products/{$product->id}", [
            'name' => 'Updated Name',
            'price' => 150.00,
            'in_stock' => true,
            'category_id' => $category->id,
            // Description is optional in update
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success', // Changed from 'success' => true
                     'message' => 'Product updated successfully',
                 ]);

        $this->assertDatabaseHas('products', ['name' => 'Updated Name']);
    }

    public function test_it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v2/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success', // Changed from 'success' => true
                     'message' => 'Product deleted successfully',
                 ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
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

    // Add new tests for validation
    public function test_it_validates_required_fields_when_creating_product()
    {
        $response = $this->postJson('/api/v2/products', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'in_stock', 'category_id']);
    }

    public function test_it_allows_optional_description()
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/v2/products', [
            'name' => 'No Description',
            'price' => 99.99,
            'in_stock' => true,
            'stock' => 5,
            'category_id' => $category->id,
            // No description provided
        ]);

        $response->assertStatus(201);
    }
}