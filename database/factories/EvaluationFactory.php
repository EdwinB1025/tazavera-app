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
        $axes = ['aroma', 'flavor', 'aftertaste', 'acidity', 'sweetness', 'mouthfeel'];

        return [
            'evaluator_role' => 'coffeeshop',
            'extraction_method' => fake()->randomElement(['V60', 'Espresso', 'Chemex', 'Aeropress']),
            'descriptive' => [
                'roast_level' => fake()->randomElement(['light', 'medium_light', 'medium', 'medium_dark', 'dark']),
                'main_tastes' => fake()->randomElements(['salty', 'sour', 'sweet', 'bitter', 'umami'], 2),
                'axis' => array_map(fn($axis) => [
                    'axis' => $axis,
                    'value' => fake()->numberBetween(5, 12),
                    'note' => null,
                ], $axes),
                'cata' => collect(['fragrance_aroma', 'flavor_aftertaste', 'acidity'])
                    ->flatMap(function ($dimension) {
                        $specificTastes = OlfactoryTaxonomy::level(2)->get();
                        $ids = $specificTastes->pluck('id')->all();

                        $randomTastes = fake()->randomElements($ids, 1);

                        $level1Tastes = OlfactoryTaxonomy::find($randomTastes)->pluck('parent_id')->all();

                        $level0Tastes = OlfactoryTaxonomy::find($level1Tastes)->pluck('parent_id')->all();


                        $level2 = array_map(function ($ref) use ($dimension) {
                            return [
                                'dimension' => $dimension,
                                'ref' => $ref,
                                'level' => 2
                            ];
                        }, $randomTastes);


                        $level1 = array_map(function ($ref) use ($dimension) {
                            return [
                                'dimension' => $dimension,
                                'ref' => $ref,
                                'level' => 1
                            ];
                        }, $level1Tastes);

                        $level0 = array_map(function ($ref) use ($dimension) {
                            return [
                                'dimension' => $dimension,
                                'ref' => $ref,
                                'level' => 0
                            ];
                        }, $level0Tastes);

                        return array_merge($level2, $level1, $level0);
                    })
                    ->values()
                    ->all(),

            ],
            'affective' => [
                'is_defective' => false,
                'defect_types' => [],
                'cupping_score' => fake()->numberBetween(78, 90),
                'axis' => array_map(fn($axis) => [
                    'axis' => $axis === 'aftertaste' ? 'overall' : $axis,
                    'value' => fake()->numberBetween(5, 9),
                ], $axes),
            ],
            'note' => 'Autoevaluación inicial de la cafetería.',
        ];
    }
}
