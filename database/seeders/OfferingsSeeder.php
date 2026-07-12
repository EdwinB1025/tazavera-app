<?php

namespace Database\Seeders;

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
        Offering::factory()->count(20)->create();
    }
}
