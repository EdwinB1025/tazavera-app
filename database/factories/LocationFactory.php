<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->company(),
            'description' => fake()->sentence(10),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'web' => fake()->url(),
            'social' => fake()->url(),
            'address' => fake()->address(),
            'country' => 'España',
            'city' => fake()->randomElement(['Barcelona', 'Madrid', 'Valencia', 'Sevilla']),
            'postal_code' => fake()->postcode(),
            'latitud' => fake()->latitude(),
            'longitud' => fake()->longitude(),

        ];
    }
}
