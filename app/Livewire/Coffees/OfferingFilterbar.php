<?php

namespace App\Livewire\Coffees;

use App\Models\Coffee;
use App\Models\Location;
use App\Models\OlfactoryTaxonomy;
use Livewire\Component;

class OfferingFilterbar extends Component
{
    public function render()
    {
        return view('livewire.coffees.offering-filterbar', [
            'cities' => Location::distinct()->orderBy('city')->pluck('city'),
            'origins' => Coffee::all()->pluck('extrinsics.origin.country')->unique()->sort()->values(),
            'processes' => Coffee::all()->pluck('extrinsics.process')->unique()->sort()->values(),
            'main_tastes' => OlfactoryTaxonomy::level(0)->orderBy('name_es')->get(),
            'specific_tastes' => OlfactoryTaxonomy::level([1, 2])->orderBy('name_es')->get(),
        ]);
    }
}
