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
        Log::info('EvaluationController@store request', $request->all());

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

        return redirect()->route('evaluations');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()->route('evaluations');
    }
}
