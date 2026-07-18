<?php

namespace App\Models;

use Database\Factories\OfferingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

#[Fillable(['location_id', 'coffee_id', 'evaluation_count', 'defective_evaluation_count', 'consensus', 'concordance', 'verification_status'])]
class Offering extends Model
{
    /** @use HasFactory<OfferingFactory> */
    use HasFactory;

    /** We assign the responsability of retriving the tastes and the score to the offering model, not the view */
    public function getTastes(int|array|null $level = null): array
    {
        $tastes_source = $this->consensus['cata_freq'] ??
            $this->evaluations()
                ->where('evaluator_role', 'coffeeshop')
                ->first()?->descriptive['cata'] ?? [];

        if ($level !== null) {
            return array_filter(
                $tastes_source,
                fn($c) => is_array($level) ?
                    in_array($c['level'], $level) : $c['level'] === $level
            );
        }

        return $tastes_source;
    }

    public function getScore(): float
    {
        return data_get($this->consensus, 'cupping_avg')
            ?? $this->evaluations()->where('evaluator_role', 'coffeeshop')->first()?->affective['cupping_score']
            ?? 0;
    }

    public static function search(Request $request)
    {

        $offerings = Offering::query()
            ->name($request->input('name'))
            ->city($request->input('city'))
            ->origin($request->input('origin'))
            ->process($request->input('process'))
            ->scoreMin($request->input('score'))
            ->tastes($request->input('main_tastes'))
            ->tastes($request->input('specific_taste'))
            ->with('coffee', 'location')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return $offerings;
    }

    /** @use  Illuminate\Database\Eloquent\Builder;
     * Queries to be used during the filtering of the Offering model with relationships
     * whereHas validates there is an existing relationship for the external entity in Eloquent**/
    #[Scope]
    protected function name(Builder $query, ?string $name): void
    {
        $query->when($name, fn($condition) => $condition->whereHas(
            'coffee',
            function ($subquery) use ($name) {
                $arrayWords = explode(' ', trim($name));
                foreach ($arrayWords as $word) {
                    $subquery->where('name', 'like', '%' . $word . '%');
                }
            }
        ));
    }

    #[Scope]
    protected function city(Builder $query, ?string $city): void
    {
        $query->when(
            $city,
            fn($condition) => $condition->whereHas(
                'location',
                fn($location) => $location->where('city', $city)
            )
        );
    }

    #[Scope]
    protected function origin(Builder $query, ?string $origin): void
    {
        $query->when(
            $origin,
            fn($condition) => $condition->whereHas(
                'coffee',
                fn($coffee) => $coffee->where('extrinsics->origin->country', $origin)
            )
        );
    }

    #[Scope]
    protected function process(Builder $query, ?string $process): void
    {
        $query->when(
            $process,
            fn($condition) => $condition->whereHas(
                'coffee',
                fn($coffee) => $coffee->where('extrinsics->process', $process)
            )
        );
    }

    #[Scope]
    protected function scoreMin(Builder $query, ?float $score): void
    {
        $consensus = Offering::query();
        $consensus->when(
            $score,
            fn($condition) => $condition->where('consensus->cupping_avg', '>=', $score)
        );

        if ($consensus->exists()) {
            $query->mergeConstraintsFrom($consensus);
        } else {
            $query->when($score, fn($condition) => $condition->whereHas(
                'evaluations',
                fn($subquery) => $subquery->where('evaluator_role', 'coffeeshop')->where('affective->cupping_score', '>=', $score)
            ));
        }
    }

    #[Scope]
    protected function tastes(Builder $query, ?array $tastes): void
    {
        $consensus = Offering::query();
        $consensus->when(
            $tastes,
            fn($condition) => $condition->where(function ($nestedquery) use ($tastes) {
                foreach ($tastes as $ref) {
                    $nestedquery->orWhereJsonContains(
                        'consensus->cata_req',
                        ['ref' => (int) $ref]
                    );
                }
            })
        );

        if ($consensus->exists()) {
            $query->mergeConstraintsFrom($consensus);
        } else {
            $query->when(
                $tastes,
                fn($condition) => $condition->whereHas(
                    'evaluations',
                    function ($evaluations) use ($tastes) {
                        $evaluations->where(
                            function ($nestedquery) use ($tastes) {
                                foreach ($tastes as $ref) {
                                    $nestedquery->orWhereJsonContains('descriptive->cata', ['ref' => (int) $ref]);
                                }
                            }
                        );
                    }
                )

            );
        }
    }

    protected function casts(): array
    {
        return [
            'consensus' => 'array',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function coffee(): BelongsTo
    {
        return $this->belongsTo(Coffee::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
