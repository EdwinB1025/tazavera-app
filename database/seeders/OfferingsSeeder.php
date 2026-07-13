<?php

namespace Database\Seeders;

use App\Models\Coffee;
use App\Models\Location;
use App\Models\Offering;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfferingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coffees = Coffee::all();
        foreach (Location::all() as $location) {
            foreach ($coffees->random(3) as $coffee) {
                Offering::factory()->create([
                    'location_id' => $location->id,
                    'coffee_id' => $coffee->id,
                ]);
            }
        }
    }
}
