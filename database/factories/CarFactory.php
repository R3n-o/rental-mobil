<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->randomElement(['Avanza', 'Xenia', 'Innova', 'Fortuner', 'Pajero', 'Rush', 'Terios']),
            'brand' => fake()->randomElement(['Toyota', 'Honda', 'Daihatsu', 'Mitsubishi', 'Suzuki']),
            'model' => fake()->year(),
            'plate_number' => strtoupper(fake()->randomLetter() . fake()->randomLetter() . ' ' . fake()->numerify('####') . ' ' . fake()->randomLetter() . fake()->randomLetter() . fake()->randomLetter()),
            'daily_rent_price' => fake()->numberBetween(200000, 1000000),
            'is_available' => true,
            'image' => null,
        ];
    }

    /**
     * Indicate that the car is not available.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}
