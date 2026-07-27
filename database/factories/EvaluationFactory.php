<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\OlfactoryTaxonomy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $axes = ['aroma', 'flavor', 'aftertaste', 'acidity', 'sweetness', 'mouthfeel', 'overall'];
        $noteKeys = ['aroma', 'flavor_aftertaste', 'acidity', 'sweetness', 'mouthfeel', 'overall'];

        // real nodes desde la taxonomía, filtrados por categoría → [{ref, level}]
        $cata = fn(array $levels, string $category, int $count) =>
        OlfactoryTaxonomy::tastes($levels, $category)->get()
            ->shuffle()
            ->take($count)
            ->map(fn($n) => ['ref' => $n->id, 'level' => $n->level])
            ->values()
            ->all();

        $defects = $cata([0, 1, 2], 'defects', 2);

        return [
            'evaluator_role'    => 'coffeeshop',
            'extraction_method' => fake()->randomElement(['V60', 'Espresso', 'Chemex', 'Aeropress']),

            'descriptive' => [
                'roast_level' => fake()->randomElement(['light', 'medium_light', 'medium', 'medium_dark', 'dark']),
                'main_tastes' => $cata([0], 'main_tastes', 2),
                'axis' => collect($axes)->mapWithKeys(fn($a) => [$a => fake()->numberBetween(5, 12)])->all(),
                'cata' => [
                    'aroma'             => $cata([0, 1, 2], 'aromatics', 3),
                    'flavor_aftertaste' => $cata([0, 1, 2], 'aromatics', 3),
                ],
                'note' => collect($noteKeys)->mapWithKeys(fn($k) => [$k => null])->all(),
            ],

            'affective' => [
                'is_defective'  => !empty($defects),
                'cupping_score' => fake()->numberBetween(78, 90),
                'axis' => collect($axes)->mapWithKeys(fn($a) => [$a => fake()->numberBetween(5, 9)])->all(),
                'cata' => [
                    'defects'   => $defects,
                    'mouthfeel' => $cata([0, 1], 'mouthfeel', 2),
                ],
                'note' => collect($noteKeys)->mapWithKeys(fn($k) => [$k => null])->all(),
            ],

            'note' => 'Autoevaluación inicial de la cafetería.',
        ];
    }
}
