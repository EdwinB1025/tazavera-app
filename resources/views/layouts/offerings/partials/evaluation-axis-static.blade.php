@props(['item', 'evaluation'])

@php
$axis = $item->name;
$cataTarget = $item->cataTarget;
$showFlavors = !is_null($cataTarget);
$axes = explode('_', $axis);
$cataRefs = $cataTarget ? (data_get($evaluation, $cataTarget) ?? []) : [];
@endphp

<div class="tz-form-main p-0 pb-2" x-data="{ open: true }">
    {{-- Encabezado con toggle --}}
    <div class="tz-card-section m-0 mb-3 !rounded-b-none p-2">
        <button type="button" @click="open = !open"
            class="group flex items-center justify-between gap-2 py-2 px-2 w-full text-left">
            <flux:subheading class="text-lg">{{ __('axis.'.$axis) }}</flux:subheading>
            <span class="opacity-80 group-hover:opacity-100 transition-opacity" style="color: var(--color-primary)">
                <flux:icon.chevron-down x-show="!open" class="w-4 h-4" />
                <flux:icon.chevron-up x-show="open" x-cloak class="w-4 h-4" />
            </span>
        </button>
    </div>

    <div x-show="open" x-collapse class="flex flex-col md:flex-row gap-6 p-4">
        @if($axis !== 'overall')
        {{-- Seccion Descriptive --}}
        <div class="flex-1 min-w-0 flex flex-col items-stretch !p-2 gap-4 tz-right-border-card">
            <flux:subheading class="tz-subtitle">Evaluación Descriptiva</flux:subheading>

            <div class="flex flex-col gap-4 p-0 w-full">
                <flux:label>Intensidad</flux:label>
                <div class="tz-card-section flex flex-col gap-2 mx-2">
                    @foreach($axes as $axisIntensity)
                    <x-layouts::offerings.partials.descriptive-intensity-static
                        :axis="$axisIntensity"
                        :value="$evaluation->descriptive['axis'][$axisIntensity] ?? 0" />
                    @endforeach
                </div>
            </div>

            @if($showFlavors)
            <div class="flex flex-col gap-4 w-full">
                <flux:label>{{ $axis == 'overall' ? 'Defectos' : 'Atributos cata' }}</flux:label>
                <div class="tz-card-section p-4 mx-2">
                    <x-layouts::offerings.partials.cata-wheel-static :cataFreq="$cataRefs" />
                </div>
            </div>
            @endif

            <div class="flex flex-col gap-1 p-2">
                <flux:label>Notas:</flux:label>
                <flux:text>{{ $evaluation->descriptive['note'][$axis] ?? null }}</flux:text>
            </div>
        </div>
        @endif

        {{-- Seccion Affective --}}
        <div class="flex-1 min-w-0 flex flex-col items-stretch p-2 gap-4">
            <flux:subheading class="tz-subtitle">Evaluación Afectiva</flux:subheading>

            <div class="flex flex-col gap-4 w-full">
                <div class="tz-card-section flex flex-col gap-2 mx-2">
                    @foreach($axes as $axisAffective)
                    <x-layouts::offerings.partials.consensus-axis
                        :axis="$axisAffective"
                        :value="$evaluation->affective['axis'][$axisAffective] ?? 0" />
                    @endforeach
                </div>

                @if($axis == 'overall')
                <div class="flex flex-col gap-2">
                    <flux:label>Taza con defectos</flux:label>
                    @if($evaluation->affective['is_defective'] ?? false)
                    <span class="tz-offering-defective" data-flux-icon="alert-circle">Sí</span>
                    @else
                    <flux:text>No</flux:text>
                    @endif
                </div>

                <div class="flex flex-col gap-4 w-full">
                    <flux:label>Defectos</flux:label>
                    <div class="tz-card-section p-4 mx-2">
                        <x-layouts::offerings.partials.cata-wheel-static :cataFreq="$cataRefs" />
                    </div>
                </div>
                @endif

                @php
                $affectiveNote = $axis == 'overall' ? $evaluation->note : ($evaluation->affective['note'][$axis] ?? null);
                @endphp
                <div class="flex flex-col gap-1 p-2">
                    <flux:label>Notas:</flux:label>
                    <flux:text>{{ $affectiveNote }}</flux:text>
                </div>
            </div>
        </div>
    </div>
</div>
