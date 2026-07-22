<?php

namespace App\Livewire\Evaluations;

use App\Models\CataAttribute;
use App\Models\Offering;
use App\Models\OlfactoryTaxonomy;
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
        'main_tastes' => [],                    // hasta 2: salty/sour/sweet/bitter/umami
        'axis' => [
            'aroma'      => null,
            'flavor'     => null,
            'aftertaste' => null,
            'acidity'    => null,
            'sweetness'  => null,
            'mouthfeel'  => null,
            'overall'    => null,
        ],
        'cata' => [
            'aroma'   => [],          // ids seleccionados (niveles 0/1/2)
            'flavor_aftertaste' => [],
        ],
        'note' => [
            'aroma'      => null,
            'flavor_aftertaste'     => null,
            'acidity'    => null,
            'sweetness'  => null,
            'mouthfeel'  => null,
            'overall'    => null,
        ],
    ];

    // Estado afectivo
    public array $affective = [
        'is_defective'  => false,
        'cupping_score' => null,                    // hasta 2: salty/sour/sweet/bitter/umami
        'axis' => [
            'aroma'      => null,
            'flavor'     => null,
            'aftertaste' => null,
            'acidity'    => null,
            'sweetness'  => null,
            'mouthfeel'  => null,
            'overall'    => null,
        ],
        'cata' => [
            'defects'   => [],                  // ids de nodos con categoria 'defects'
            'mouthfeel' => [],                  // ids de nodos mouthfeel (115-120)
        ],
        'note' => [
            'aroma'      => null,
            'flavor_aftertaste'     => null,
            'acidity'    => null,
            'sweetness'  => null,
            'mouthfeel'  => null,
            'overall'    => null,
        ],
    ];

    // Listas fijas (fuente única para vista y validación)

    public const COMPONENTS = [
        [
            'name' => 'aroma',
            'cataTarget' => 'descriptive.cata.aroma',
            'cataNodes' => 'aromatics',
        ],
        [
            'name' => 'flavor_aftertaste',
            'cataTarget' => 'descriptive.cata.flavor_aftertaste',
            'cataNodes' => 'aromatics',

        ],
        [
            'name' => 'acidity',
            'cataTarget' => null,
            'cataNodes' => null,
        ],
        [
            'name' => 'sweetness',
            'cataTarget' => null,
            'cataNodes' => null,

        ],
        [
            'name' => 'mouthfeel',
            'cataTarget' => 'affective.cata.mouthfeel',
            'cataNodes' => 'mouthfeel',
        ],
        [
            'name' => 'overall',
            'cataTarget' => 'affective.cata.defects',
            'cataNodes' => 'defects',
        ],
    ];
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

        $nodesByKey = [
            'aromatics' => OlfactoryTaxonomy::tastes([0, 1, 2], 'aromatics')->get(),
            'defects'   => OlfactoryTaxonomy::tastes([0, 1, 2], 'defects')->get(),
            'mouthfeel' => OlfactoryTaxonomy::tastes([0, 1], 'mouthfeel')->get(),
        ];

        $components = array_map(function ($c) use ($nodesByKey) {
            $c['cataNodes'] = $c['cataNodes'] ? $nodesByKey[$c['cataNodes']] : null;
            return $c;
        }, self::COMPONENTS);

        return view(
            'livewire.evaluations.evaluation-form',
            ['components' => $components,]
        );
    }

    public function updateIsDeffective(): void
    {
        $this->affective['is_defective'] = !empty($this->affective['cata']['defects']);
    }

    public function saveDraft(): void
    {
        //
    }

    public function closeEvaluation(): void
    {
        //
    }
}
