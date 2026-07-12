<?php

namespace Database\Factories;

use App\Models\Coffee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Coffee>
 */
class CoffeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regions = [
            'Colombia' => ['Huila', 'Nariño', 'Cauca', 'Tolima', 'Antioquia'],
            'Etiopía' => ['Yirgacheffe', 'Sidamo', 'Guji', 'Harrar'],
            'Kenia' => ['Nyeri', 'Kirinyaga', 'Embu'],
            'Brasil' => ['Minas Gerais', 'Cerrado', 'Mogiana'],
            'Guatemala' => ['Antigua', 'Huehuetenango', 'Atitlán'],
        ];

        $country = fake()->randomElement(array_keys($regions));

        return [
            'name' => fake()->words(3, true),
            'roastery' => fake()->company(),
            'roast_level' => fake()->randomElement(['light', 'medium_light', 'medium', 'medium_dark', 'dark']),
            'extrinsics' => [
                'country' => $country,
                'region' => fake()->randomElement($regions[$country]),
                'variety' => fake()->randomElement(['Caturra', 'Bourbon', 'Geisha', 'Typica', 'SL28']),
                'process' => fake()->randomElement(['Lavado', 'Natural', 'Honey']),
                'altitude' => fake()->numberBetween(1200, 2200),
            ],
        ];
    }
}
