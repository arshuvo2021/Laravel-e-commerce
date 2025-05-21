<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $stock = $this->faker->numberBetween(0, 100);
        
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $stock,
            'in_stock' => $stock > 0,
            'category_id' => Category::factory(),
        ];
    }
}
