@props(['offering' => null, 'evaluation' => null])

@php
$evaluation = $evaluation ?? $offering->evaluations()->coffeeshop()->first();
$components = \App\Livewire\Evaluations\EvaluationForm::COMPONENTS;

if ($evaluation) {
    $cuppingScore = $evaluation->affective['cupping_score'] ?? 0;

    if ($cuppingScore >= 90) {
        $badgeColor = 'tz-rating-exceptional';
    } elseif ($cuppingScore >= 85) {
        $badgeColor = 'tz-rating-excellent';
    } elseif ($cuppingScore >= 80) {
        $badgeColor = 'tz-rating-good';
    } else {
        $badgeColor = 'tz-rating-commercial';
    }
}
@endphp

@if(!$evaluation)
<flux:text class="!italic !text-secondary">No hay evaluación de cafetería registrada para este café.</flux:text>
@else
<div class="flex flex-col gap-4">
    <div class="tz-form-main flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <flux:subheading class="tz-subtitle">Contexto</flux:subheading>
            <flux:badge class="{{ $badgeColor }}">
                <div data-flux-card-value>{{ $cuppingScore }}</div>
                <div data-flux-card-scale>/100</div>
            </flux:badge>
        </div>
        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Método de extracción</span>
                <span>{{ $evaluation->extraction_method ? ucwords(str_replace('_', ' ', $evaluation->extraction_method)) : 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Nivel de tueste</span>
                <span>{{ ($evaluation->descriptive['roast_level'] ?? null) ? __('roast_level.'.$evaluation->descriptive['roast_level']) : 'N/A' }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <span class="tz-offering-label">Sabores principales</span>
            <x-layouts::offerings.partials.cata-wheel-static :cataFreq="$evaluation->descriptive['main_tastes'] ?? []" />
        </div>
    </div>

    @foreach($components as $item)
    @php $item = (object) $item; @endphp
    <x-layouts::offerings.partials.evaluation-axis-static :item="$item" :evaluation="$evaluation" />
    @endforeach
</div>
@endif
