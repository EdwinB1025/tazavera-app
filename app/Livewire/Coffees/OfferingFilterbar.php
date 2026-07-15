<?php

namespace App\Livewire\Coffees;

use App\Models\CataAttribute;
use App\Models\Coffee;
use App\Models\Location;
use App\Models\Offering;
use Livewire\Component;

class OfferingFilterbar extends Component
{
    public function render()
    {
        return view('livewire.coffees.offering-filterbar', [
            'cities' => Location::distinct()->orderBy('city')->pluck('city'),
            'origins' => Coffee::all()->pluck('extrinsics.country')->unique()->sort()->values(),
            'processes' => Coffee::all()->pluck('extrinsics.process')->unique()->sort()->values(),
            'main_tastes' => CataAttribute::query()->distinct()->orderBy('category')->pluck('category'),
            'specific_tastes' => CataAttribute::query()->distinct()->orderBy('sub_category')->pluck('sub_category'),
        ]);
    }
}
