<?php

namespace Database\Factories;

use App\Models\Evaluation;
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
            'extraction_method' => 'cupping',
            'descriptive' => [
                'roast_level' => fake()->randomElement(['light', 'medium_light', 'medium', 'medium_dark', 'dark']),
                'main_tastes' => fake()->randomElements(['salty', 'sour', 'sweet', 'bitter', 'umami'], 2),
                'axis' => array_map(fn($axis) => [
                    'axis' => $axis,
                    'value' => fake()->numberBetween(5, 12),
                    'note' => null,
                ], $axes),
                'cata' => collect(['fragrance_aroma', 'flavor_aftertaste', 'acidity'])
                    ->flatMap(fn($dimension) => array_map(
                        fn($ref) => ['dimension' => $dimension, 'ref' => $ref],
                        fake()->randomElements(range(15, 45), fake()->numberBetween(1, 5))
                    ))
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
