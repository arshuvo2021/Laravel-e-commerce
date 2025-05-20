<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
   // ProductFactory.php
public function definition()
{
    return [
        'name' => $this->faker->word,
        'description' => $this->faker->sentence,
        'price' => $this->faker->randomFloat(2, 1, 100),
        'stock' => $this->faker->numberBetween(0, 100),
        'category_id' => Category::factory(),  // ensures category exists
    ];
}

}
