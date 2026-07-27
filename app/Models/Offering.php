<?php

namespace App\Models;

use Database\Factories\OfferingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;

#[Fillable(['location_id', 'coffee_id', 'evaluation_count', 'defective_evaluation_count', 'consensus', 'concordance', 'verification_status'])]
class Offering extends Model
{
    /** @use HasFactory<OfferingFactory> */
    use HasFactory;

    public const AXIS = ['aroma', 'flavor', 'aftertaste', 'acidity', 'sweetness', 'mouthfeel', 'overall'];

    /**
     * Recalcula y guarda el consenso de la offering cuando ya hay más de 5
     * evaluaciones cerradas de especialistas. axis_avg promedia affective.axis;
     * cata_freq y main_tastes cuentan frecuencia de refs entre esas evaluaciones;
     * cupping_avg descuenta 4 puntos por cada taza defectuosa, normalizado a un
     * panel de 5 tazas (penalización -4d del protocolo SCA; -2u queda pendiente).
     */

    public static function updateConsensus(string $id): void
    {
        self::updateAvgAxis($id);
        self::updateAvgScore($id);
        self::updateCataFreq($id);
        self::updateMainTastes($id);
    }

    public static function updateAvgAxis(string $id): void
    {
        $evaluations = self::getConsensusEvaluations($id);

        $axisKeys = self::AXIS;
        /** Average per axis of al the evaluations that meet the criteria */
        $axisAvg = collect($axisKeys)
            ->mapWithKeys(
                fn($axis) => [
                    $axis => round($evaluations->avg(
                        fn($e) => $e->affective['axis'][$axis] ?? 0
                    ), 2),
                ]
            )->all();

        self::mergeConsensus($id, ['axis_avg' => $axisAvg]);
    }

    public static function updateAvgScore(string $id): void
    {
        $evaluations = self::getConsensusEvaluations($id);

        $defectiveCount = $evaluations
            ->filter(fn($e) => $e->affective['is_defective'] ?? false)
            ->count();

        $avgCuppingRaw = $evaluations->avg(fn($e) => $e->affective['cupping_score'] ?? 0);
        $evaluationsCount = $evaluations->count();
        $cuppingAvg = round((($avgCuppingRaw - 4 * $defectiveCount / $evaluationsCount)) * 4) / 4;

        self::mergeConsensus($id, ['cupping_avg' => $cuppingAvg]);
    }

    public static function updateCataFreq(string $id): void
    {
        $evaluations = self::getConsensusEvaluations($id);

        $cataRefs = $evaluations->flatMap(
            fn($e) => array_merge(
                ($e->descriptive['cata']['aroma'] ?? []),
                ($e->descriptive['cata']['flavor_aftertaste'] ?? []),
                ($e->affective['cata']['mouthfeel'] ?? []),
                ($e->affective['cata']['defects'] ?? []),
            )
        );

        self::mergeConsensus($id, ['cata_freq' => self::addCountToCataRefs($cataRefs, withParent: true)]);
    }

    public static function updateMainTastes(string $id): void
    {
        $evaluations = self::getConsensusEvaluations($id);
        $mainTasteRefs = $evaluations->flatMap(fn($e) => $e->descriptive['main_tastes'] ?? []);

        self::mergeConsensus($id, ['main_tastes' => self::addCountToCataRefs($mainTasteRefs, withParent: false)]);
    }

    /**
     * Fusiona $partial sobre el consensus existente en vez de reemplazarlo entero,
     * para que cada método (axis_avg/cupping_avg/cata_freq/main_tastes) pueda
     * llamarse solo o en secuencia sin borrar lo que ya calcularon los demás.
     */
    private static function mergeConsensus(string $id, array $partial): void
    {
        $offering = self::find($id);
        $offering->update([
            'consensus' => array_merge($offering->consensus ?? [], $partial),
        ]);
    }

    private static function getConsensusEvaluations(string $id): Collection
    {
        $offering = self::find($id);
        return $offering->evaluations()
            ->specialist()
            ->statusIs('closed')
            ->get();
    }

    public function getConsensus(): ?array
    {
        return $this->consensus;
    }

    private static function addCountToCataRefs($refs, bool $withParent): array
    {
        return collect($refs)
            ->groupBy('ref')
            ->map(function ($group) use ($withParent) {
                $item = [
                    'ref'   => (int) $group->first()['ref'],
                    'level' => $group->first()['level'],
                ];

                if ($withParent) {
                    $item['parent_id'] = $group->first()['parent_id'];
                }

                $item['count'] = $group->count();

                return $item;
            })
            ->values()
            ->all();
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
