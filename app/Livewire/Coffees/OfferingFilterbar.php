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
            'main_tastes' => OlfactoryTaxonomy::tastes(0, 'main_tastes')->orderBy('name_es')->get(),
            'specific_tastes' => OlfactoryTaxonomy::tastes([0])->orderBy('name_es')->get(),
        ]);
    }
}
