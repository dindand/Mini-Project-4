<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state (Slide 29).
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'sku' => fake()->unique()->bothify('SKU-####'),
            'name' => ucwords(fake()->words(3, true)),
            'price' => fake()->randomFloat(2, 10000, 500000),
            'stock' => fake()->numberBetween(0, 100),
            'minimum_stock' => fake()->numberBetween(5, 15),
        ];
    }
}
