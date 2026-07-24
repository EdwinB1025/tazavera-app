<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterOfferingRequest;
use App\Models\Offering;
use Illuminate\Http\Request;

class OfferingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterOfferingRequest $request)
    {
        $offerings = Offering::search($request);

        return view('layouts.offerings.index', compact('offerings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Offering $offering)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offering $offering)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offering $offering)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offering $offering)
    {
        //
    }
}
