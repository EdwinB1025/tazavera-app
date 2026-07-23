<?php

namespace App\Models;

use Database\Factories\EvaluationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

#[Fillable(['offering_id', 'evaluator_id', 'evaluator_role', 'extraction_method', 'status', 'descriptive', 'affective', 'note'])]
class Evaluation extends Model
{
    /** @use HasFactory<EvaluationFactory> */
    use HasFactory;

    public static function search(Request $request)
    {
        return Evaluation::query()
            ->evaluator($request->input('id')) //query de evaluacion individual
            ->coffeeId($request->input('coffee_id')) //query de cafe evaluado por id
            ->city($request->input('city')) //query de ciudad en cafeteria
            ->locationId($request->input('location_id')) //query de cafeteria por id
            ->when($request->input('score'), fn($q) => $q->scoreMin((float) $request->input('score'))) // query de puntaje
            ->with('offering.coffee', 'offering.location')
            ->latest()
            ->paginate(12)
            ->withQueryString();
    }


    #[Scope]
    protected function hasCataRefs(Builder $query, array $refs, ?array $dimensions = null): void
    {
        $dimensions ??= ['aroma', 'flavor_aftertaste'];
        $query->when(
            $refs,
            fn($q) => $q->where(
                function ($sub) use ($refs, $dimensions) {
                    foreach ($refs as $ref) {
                        foreach ($dimensions as $key) {
                            $sub->orWhereJsonContains("descriptive->cata->{$key}", ['ref' => (int)$ref,]);
                        }
                    }
                }
            )
        );
    }

    #[Scope]
    protected function hasCataLevels(Builder $query, array $levels, ?array $dimensions = null): void
    {
        $dimensions ??= ['aroma', 'flavor_aftertaste'];
        $query->when(
            $levels,
            fn($q) => $q->where(
                function ($sub) use ($levels, $dimensions) {
                    foreach ($levels as $level) {
                        foreach ($dimensions as $key) {
                            $sub->orWhereJsonContains("descriptive->cata->{$key}", ['level' => (int)$level,]);
                        }
                    }
                }
            )
        );
    }

    #[Scope]
    protected function coffeeshop(Builder $query): void
    {
        $query->where('evaluator_role', 'coffeeshop');
    }

    #[Scope]
    protected function specialist(Builder $query): void
    {
        $query->where('evaluator_role', 'specialist');
    }

    #[Scope]
    protected function scoreMin(Builder $query, float $score): void
    {
        $query->when(
            fn($q) => $q->where('affective->cupping_score', '>=', $score)
        );
    }

    #[Scope]
    protected function coffeeId(Builder $query, ?int $coffeeId): void
    {
        $query->when(
            $coffeeId,
            fn($q) => $q->whereHas(
                'offering',
                fn($offering) => $offering->coffeeId($coffeeId)
            )
        );
    }

    #[Scope]
    protected function city(Builder $query, ?string $city): void
    {
        $query->when(
            $city,
            fn($q) => $q->whereHas(
                'offering',
                fn($offering) => $offering->city($city)
            )
        );
    }

    #[Scope]
    protected function process(Builder $query, ?string $process): void
    {
        $query->when(
            $process,
            fn($q) => $q->whereHas(
                'offering',
                fn($offering) => $offering->process($process)
            )
        );
    }

    #[Scope]
    protected function locationId(Builder $query, ?int $locationId): void
    {
        $query->when(
            $locationId,
            fn($q) => $q->whereHas(
                'offering',
                fn($offering) => $offering->locationId($locationId)
            )
        );
    }

    #[Scope]
    protected function evaluator(Builder $query, int $userId): void
    {
        $query->when(
            $userId,
            fn($q) => $q->where('evaluator_id', $userId)
        );
    }

    public function offering(): BelongsTo
    {
        return $this->belongsTo(Offering::class);
    }

    protected function casts(): array
    {
        return [
            'descriptive' => 'array',
            'affective' => 'array',
        ];
    }
}
