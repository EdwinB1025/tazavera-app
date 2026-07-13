<?php

namespace Database\Seeders;

use App\Models\OlfactoryTaxonomy;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                CoffeshopSeeder::class,
                CoffeesSeeder::class,
                OfferingsSeeder::class,
                ProvisionalEvaluationSeeder::class,
                OlfactoryTaxonomySeeder::class,
            ]
        );
    }
}
