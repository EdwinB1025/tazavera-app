<?php

namespace Database\Factories;

use App\Models\Coffee;
use App\Models\Location;
use App\Models\Offering;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offering>
 */
class OfferingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'verification_status' => 'provisional',
        ];
    }
}
