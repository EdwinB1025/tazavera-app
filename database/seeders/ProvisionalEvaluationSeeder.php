<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\Location;
use App\Models\Offering;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvisionalEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Offering::all() as $offering) {
            Evaluation::factory()->create([
                'offering_id' => $offering->id,
                'evaluator_id' => Location::find($offering->location_id)->user_id,
            ]);
        }
    }
}
