<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),           // اسم عشوائي
            'description' => $this->faker->sentence(), // وصف عشوائي
            'price' => $this->faker->randomFloat(2, 10, 500), // سعر بين 10 و 500
            'quantity' => $this->faker->numberBetween(1, 100), // كمية بين 1 و 100
            'discount' => $this->faker->numberBetween(0, 30),  // خصم بين 0% و 30%
          //
        ];
    }
}
