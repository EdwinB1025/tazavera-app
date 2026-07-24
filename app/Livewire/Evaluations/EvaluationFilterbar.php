<?php

namespace App\Livewire\Evaluations;

use App\Models\Coffee;
use App\Models\Evaluation;
use App\Models\Location;
use Livewire\Component;

class EvaluationFilterbar extends Component
{
    public function render()
    {
        return view('livewire.evaluations.partials.evaluation-filterbar', [
            'cities'    => Location::distinct()->orderBy('city')->pluck('city'),
            'processes' => Coffee::all()->pluck('extrinsics.process')->unique()->sort()->values(),
            'locations' => Evaluation::evaluator(auth()->id())->with('offering.location')->get()->pluck('offering.location')->filter()->unique('id')->values(),
            'coffees' => Evaluation::evaluator(auth()->id())->with('offering.coffee')->get()->pluck('offering.coffee')->filter()->unique('id')->values(),
        ]);
    }
}
