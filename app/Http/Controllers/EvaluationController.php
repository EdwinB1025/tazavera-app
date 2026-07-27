<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Offering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->merge(['id' => auth()->id()]);

        $evaluations = Evaluation::search($request);

        return view('layouts.evaluations.index', compact('evaluations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $offering = Offering::find($request->input('offering'));
        $offering->load('location', 'coffee');
        return view('layouts.evaluations.create', compact('offering'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $affective = json_decode($request->input('affective'), true);
        $affective['cupping_score'] = $this->computeCuppingScore($affective['axis']);


        Evaluation::create([
            'offering_id'       => $request->input('offering_id'),
            'evaluator_id'      => auth()->id(),
            'evaluator_role'    => $request->input('evaluator_role'),
            'extraction_method' => $request->input('extraction_method'),
            'status'            => $request->input('status'),
            'descriptive'       => json_decode($request->input('descriptive'), true),
            'affective'         => $affective,
            'note'              => $request->input('note'),
        ]);

        return redirect()->route('evaluations.create', ['offering' => $request->input('offering_id')]);
    }

    /**
     * Recalcula y guarda el consenso de la offering cuando ya hay más de 5
     * evaluaciones cerradas de especialistas. axis_avg promedia affective.axis;
     * cata_freq y main_tastes cuentan frecuencia de refs entre esas evaluaciones;
     * cupping_avg descuenta 4 puntos por cada taza defectuosa, normalizado a un
     * panel de 5 tazas (penalización -4d del protocolo SCA; -2u queda pendiente).
     */
    private function updateConsensus(Offering $offering): void
    {

        $axisKeys = ['aroma', 'flavor', 'aftertaste', 'acidity', 'sweetness', 'mouthfeel', 'overall'];
        $axisAvg = collect($axisKeys)->mapWithKeys(fn($axis) => [
            $axis => round($evaluations->avg(fn($e) => $e->affective['axis'][$axis] ?? 0), 2),
        ])->all();

        $defectiveCount = $evaluations->filter(fn($e) => $e->affective['is_defective'] ?? false)->count();
        $cuppingRaw = $evaluations->avg(fn($e) => $e->affective['cupping_score'] ?? 0);
        $cuppingAvg = round(($cuppingRaw - 4 * $defectiveCount * 5 / $n) * 4) / 4;

        $cataRefs = $evaluations->flatMap(fn($e) => [
            ...($e->descriptive['cata']['aroma'] ?? []),
            ...($e->descriptive['cata']['flavor_aftertaste'] ?? []),
            ...($e->affective['cata']['mouthfeel'] ?? []),
            ...($e->affective['cata']['defects'] ?? []),
        ]);
        $mainTasteRefs = $evaluations->flatMap(fn($e) => $e->descriptive['main_tastes'] ?? []);

        $offering->update([
            'consensus' => [
                'axis_avg'    => $axisAvg,
                'cupping_avg' => $cuppingAvg,
                'main_tastes' => $this->countCataRefs($mainTasteRefs, withParent: false),
                'cata_freq'   => $this->countCataRefs($cataRefs, withParent: true),
            ],
        ]);
    }

    /**
     * Agrupa refs de cata repetidos entre evaluaciones y cuenta su frecuencia.
     * El parent_id, si se pide, se toma del que ya guardó descriptor-cascade.blade.php
     * al crear la evaluación (no se re-consulta la taxonomía).
     */
    private function countCataRefs($refs, bool $withParent): array
    {
        return collect($refs)->groupBy('ref')->map(function ($group) use ($withParent) {
            $item = [
                'ref'   => (int) $group->first()['ref'],
                'level' => $group->first()['level'],
                'parent_id' => $group->fist()['parent_id'],
                'count' => $group->count(),
            ];

            return $item;
        })->values()->all();
    }

    /**
     * Coeficiente SCA (versión simplificada): S = 0.65625 * Σh_i + 52.75,
     * redondeado a 0.25. No incluye penalizaciones por -2u -4d porque esas
     * son agregados entre varias evaluaciones de la misma oferta, no de una sola.
     * "fragrance" se duplica a partir de "aroma" (no existe eje separado en el modelo).
     */
    private function computeCuppingScore(array $axis): float
    {
        $sum = $axis['aroma']       // fragrance (duplicado de aroma)
            + $axis['aroma']
            + $axis['flavor']
            + $axis['aftertaste']
            + $axis['acidity']
            + $axis['sweetness']
            + $axis['mouthfeel']
            + $axis['overall'];

        $score = 0.65625 * $sum + 52.75;

        return round($score * 4) / 4;
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        $evaluation->load('offering.location', 'offering.coffee');
        $offering = $evaluation->offering;

        return view('layouts.evaluations.create', compact('offering', 'evaluation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {

        $affective = $evaluation->affective;

        if ($request->input('affective')) {
            $affective = json_decode($request->input('affective'), true);
            $affective['cupping_score'] = $this->computeCuppingScore($affective['axis']);
        }

        $evaluation->update([
            'evaluator_role'    => $request->input('evaluator_role') ?? $evaluation->evaluator_role,
            'extraction_method' => $request->input('extraction_method') ?? $evaluation->extraction_method,
            'status'            => $request->input('status') ?? $evaluation->status,
            'descriptive'       => $request->input('descriptive') ? json_decode($request->input('descriptive'), true) : $evaluation->descriptive,
            'affective'         => $affective,
            'note'              => $request->input('note') ?? $evaluation->note,
        ]);

        /**
         * Update consensus with 5 evaluations in status closed
         */
        if ($request->input('status') === 'closed' && $evaluation->evaluator_role === 'specialist') {
            $offering = $evaluation->offering;

            $EvaluationsCount = $offering->evaluations()
                ->specialist()
                ->statusIs('closed')
                ->get()
                ->count();

            if ($EvaluationsCount >= 5) {
                Offering::updateConsensus($evaluation->offering_id);
            }
        }

        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()->back();
    }
}
