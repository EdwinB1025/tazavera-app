<?php

namespace App\Models;

use Database\Factories\EvaluationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['offering_id', 'evaluator_id', 'evaluator_role', 'extraction_method', 'status', 'descriptive', 'affective', 'note'])]
class Evaluation extends Model
{
    /** @use HasFactory<EvaluationFactory> */
    use HasFactory;

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
