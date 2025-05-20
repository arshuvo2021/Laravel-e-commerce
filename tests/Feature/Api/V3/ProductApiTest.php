<?php

namespace Tests\Feature\Api\V3;

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

        $response = $this->getJson('/api/v3/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'price',
                             'in_stock',
                             'category' => [
                                 'id',
                                 'name'
                             ]
                         ]
                     ],
                     'current_page',
                     'last_page',
                     'per_page',
                     'total'
                 ]);
    }

    public function test_it_includes_category_data()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/v3/products');

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         [
                             'id' => $product->id,
                             'category' => [
                                 'id' => $category->id,
                                 'name' => $category->name
                             ]
                         ]
                     ]
                 ]);
    }

    public function test_it_paginates_results()
    {
        Product::factory()->count(25)->create();

        $response = $this->getJson('/api/v3/products');

        $response->assertStatus(200)
                 ->assertJson([
                     'per_page' => 20,
                     'total' => 25,
                     'last_page' => 2
                 ]);
    }
} 