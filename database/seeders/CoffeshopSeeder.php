<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoffeshopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->coffeeShop()->count(10)->create();
        Location::factory()->count(10)->create(['user_id' => fn() => User::where('role', 'coffeeshop')->inRandomOrder()->first()]);
    }
}
