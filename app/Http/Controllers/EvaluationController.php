<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Offering;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $offering = Offering::find($request->input('offering'));
        $offering->load('location', 'coffee');
        return view('layouts.evaluations.index', compact('offering'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Evaluation::create([
            'offering_id'       => $request->input('offering_id'),
            'evaluator_id'      => auth()->id(),
            'evaluator_role'    => $request->input('evaluator_role'),
            'extraction_method' => $request->input('extraction_method'),
            'status'            => $request->input('status'),
            'descriptive'       => json_decode($request->input('descriptive'), true),
            'affective'         => json_decode($request->input('affective'), true),
            'note'              => $request->input('note'),
        ]);

        return redirect()->route('evaluations.create', ['offering' => $request->input('offering_id')]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        //
    }
}
