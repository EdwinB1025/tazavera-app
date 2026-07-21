<?php

namespace App\Livewire\Evaluations;

use App\Models\CataAttribute;
use App\Models\Offering;
use Livewire\Component;

class EvaluationForm extends Component
{
    // Properties of the entity to be used by the wire:model components
    public Offering $offering;

    public ?string $evaluator_role = 'specialist';
    public ?string $extraction_method = null;
    public ?string $note = null;

    // Evaluacion descriptiva (formato definido para la serializacione en el campo jazon)
    public array $descriptive = [
        'roast_level' => null,
        'main_tastes' => [],
        'axis' => [
            'aroma'      => ['value' => null, 'note' => null],
            'flavor'     => ['value' => null, 'note' => null],
            'aftertaste' => ['value' => null, 'note' => null],
            'acidity'    => ['value' => null, 'note' => null],
            'sweetness'  => ['value' => null, 'note' => null],
            'mouthfeel'  => ['value' => null, 'note' => null],
        ],
        'cata' => [
            'fragrance_aroma'   => [],   // refs seleccionados en el desplegable
            'flavor_aftertaste' => [],
            'acidity'           => [],
        ],
        'mouthfeel_descriptors' => [],   // fijos: rough/oily/smooth/mouth_drying/metallic
    ];

    // Estado afectivo
    public array $affective = [
        'is_defective' => false,
        'defect_types' => [],
        'axis' => [
            'aroma'      => null,
            'flavor'     => null,
            'aftertaste' => null,
            'acidity'    => null,
            'sweetness'  => null,
            'mouthfeel'  => null,
        ],
    ];

    // Listas fijas (fuente única para vista y validación)
    public const ROAST_LEVELS = ['light', 'medium_light', 'medium', 'medium_dark', 'dark'];
    public const MAIN_TASTES = ['salty', 'sour', 'sweet', 'bitter', 'umami'];
    public const MOUTHFEEL_DESCRIPTORS = ['rough', 'oily', 'smooth', 'mouth_drying', 'metallic'];
    public const AXES = ['aroma', 'flavor', 'aftertaste', 'acidity', 'sweetness', 'mouthfeel'];
    public const EXTRACTION_METHODS = [
        'espresso',
        'v60',
        'chemex',
        'aeropress',
        'french_press',
        'moka',
        'cold_brew',
        'siphon',
        'kalita',
    ];

    public function mount(Offering $offering): void
    {
        $this->offering = $offering->load('coffee', 'location');
    }

    public function render()
    {
        return view('livewire.evaluations.evaluation-form', ['cataAttributes' => CataAttribute::all(),]);
    }
}
