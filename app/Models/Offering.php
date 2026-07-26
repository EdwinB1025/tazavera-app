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

    /** Valor temporal mientras el cálculo real de consenso no está implementado */
    public const DEFAULT_CONSENSUS = [
        'axis_avg' => [
            'aroma'     => 7.4,
            'flavor'    => 8.1,
            'acidity'   => 8.0,
            'sweetness' => 6.5,
            'mouthfeel' => 7.0,
            'overall'   => 7.8,
        ],
        'cupping_avg' => 83.5,
        'main_tastes' => [
            ['ref' => 24, 'count' => 4, 'level' => 0],
        ],
        'cata_freq' => [
            ['ref' => 1,  'parent_id' => null, 'count' => 5, 'level' => 0],
            ['ref' => 19, 'parent_id' => 1,    'count' => 5, 'level' => 1],
            ['ref' => 20, 'parent_id' => 19,   'count' => 5, 'level' => 2],

            ['ref' => 24, 'parent_id' => null, 'count' => 7, 'level' => 0],
            ['ref' => 25, 'parent_id' => 24,   'count' => 7, 'level' => 1],
            ['ref' => 29, 'parent_id' => 25,   'count' => 4, 'level' => 2],
            ['ref' => 30, 'parent_id' => 25,   'count' => 3, 'level' => 2],
        ],
    ];

    public function getConsensus(): array
    {
        return $this->consensus ?? self::DEFAULT_CONSENSUS;
    }

    /** We assign the responsability of retriving the tastes and the score to the offering model, not the view */
    public function getCata(int|array|null $level = null): array
    {
        if (empty($this->consensus['cata_freq'])) {
            $evaluations = $this->evaluations()
                ->coffeeshop()
                ->first();
            $tastes_source = array_merge(
                $evaluations->descriptive['cata']['aroma'] ?? [],
                $evaluations->descriptive['cata']['flavor_aftertaste'] ?? []
            );
        } else {
            $tastes_source = $this->consensus['cata_freq'];
        }

        if ($level !== null) {
            return array_filter(
                $tastes_source,
                fn($c) => is_array($level) ?
                    in_array($c['level'], $level) : $c['level'] === $level
            );
        }

        return $tastes_source;
    }

    public function getMainTastes(): array
    {
        return  $this->evaluations()
            ->coffeeshop()
            ->first()->main_tastes() ??
            ($this->main_tastes() ?? []);
    }


    public function getScore(): float
    {
        return data_get($this->consensus, 'cupping_avg')
            ?? ($this->evaluations()
                ->coffeeshop()
                ->first()
                ->affective['cupping_score']
                ?? 0);
    }

    public static function search(Request $request)
    {

        $offerings = Offering::query()
            ->when(
                $request->input('id'),
                fn($q) => $q->whereKey($request->input('id'))
            )
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
        $query->when(
            $name,
            fn($q) => $q->whereHas(
                'coffee',
                fn($coffee) => $coffee->nameLike($name)
            )
        );
    }

    #[Scope]
    protected function city(Builder $query, ?string $city): void
    {
        $query->when(
            $city,
            fn($q) => $q->whereHas(
                'location',
                fn($location) => $location->city($city)
            )
        );
    }

    #[Scope]
    protected function origin(Builder $query, ?string $origin): void
    {
        $query->when(
            $origin,
            fn($q) => $q->whereHas(
                'coffee',
                fn($coffee) => $coffee->origin($origin)
            )
        );
    }

    #[Scope]
    protected function process(Builder $query, ?string $process): void
    {
        $query->when(
            $process,
            fn($q) => $q->whereHas(
                'coffee',
                fn($coffee) => $coffee->process($process)
            )
        );
    }

    #[Scope]
    protected function locationId(Builder $query, int $locationId): void
    {
        $query->where('location_id', $locationId);
    }

    #[Scope]
    protected function coffeeId(Builder $query, int $coffeeId): void
    {
        $query->where('coffee_id', $coffeeId);
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
            $query->when(
                $score,
                fn($q) => $q->whereHas(
                    'evaluations',
                    fn($evaluations) => $evaluations->coffeeshop()->scoreMin($score)
                )
            );
        }
    }

    #[Scope]
    protected function hasCataRefs(Builder $query, ?array $refs): void
    {
        $query->where(
            function ($nestedquery) use ($refs) {
                foreach ($refs as $ref) {
                    $nestedquery->WhereJsonContains(
                        'consensus->cata_req',
                        ['ref' => (int) $ref]
                    );
                }
            }
        );
    }


    #[Scope]
    protected function tastes(Builder $query, ?array $refs): void
    {
        $consensus = Offering::query();
        $consensus->when(
            $refs,
            fn($offerings) => $offerings->hasCataRefs($refs)
        );

        if ($consensus->exists()) {
            $query->mergeConstraintsFrom($consensus);
        } else {
            $query->when($refs, fn($q) => $q->whereHas(
                'evaluations',
                fn($evaluations) => $evaluations->hasCataRefs($refs)
            ));
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
