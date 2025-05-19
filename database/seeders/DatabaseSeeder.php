<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Unchunked mass insert
        $categories = Category::factory(10)->create();

        Product::factory(5000)->create([
            'category_id' => fn() => $categories->random()->id,
            'price' => rand(10, 1000)
        ]);
    }
}
