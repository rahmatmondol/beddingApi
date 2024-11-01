<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'image' => fake()->imageUrl(),
            'description' => fake()->text(),
            'skills' => fake()->text(),
            'price' => fake()->numberBetween(100.00, 500.00),
            'price_type' => fake()->randomElement(['negotiable', 'fixed']),
            'currency' => fake()->randomElement(['USD','aed']),
            'location' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'category_id' => fake()->numberBetween(1, 10),
            'user_id' => fake()->numberBetween(1, 10),
        ];
    }

}
