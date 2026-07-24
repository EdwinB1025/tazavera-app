@props([
'open' => false,
])

@php
$axis = $item->name;
$cataTarget = $item->cataTarget;
$cataNodes = $item->cataNodes;
$showFlavors = true;
if(is_null($cataTarget) || is_null($cataNodes)){$showFlavors = false;}
$axes = explode('_', $axis);
@endphp

<div class="tz-form-main p-0 pb-2" x-data="{ open: {{ $open ? 'true' : 'false' }} }">
    {{-- Encabezado con toggle --}}
    <div class="tz-card-section m-0 mb-3 !rounded-b-none p-2">
        <button type="button" @click="open = !open"
            class="group flex items-center justify-between gap-2 py-2 px-2 w-full text-left">
            <div class="flex items-center gap-3">
                <flux:subheading class="text-lg">{{__('axis.'.$axis)}}</flux:subheading>
            </div>
            <div class="flex items-center gap-2">
                <span class="opacity-80 group-hover:opacity-100 transition-opacity"
                    style="color: var(--color-primary)">
                    <flux:icon.chevron-down x-show="!open" class="w-4 h-4" />
                    <flux:icon.chevron-up x-show="open" x-cloak class="w-4 h-4" />
                </span>

            </div>
        </button>
    </div>

    {{-- Contenido expandible --}}
    <div x-show="open" x-collapse class="flex flex-col md:flex-row gap-6 p-4">
        @if($axis!=='overall')
        {{-- Seccion Descriptive --}}
        <div class="flex-1 min-w-0 flex flex-col items-stretch !p-2 gap-4 tz-right-border-card">
            <flux:subheading class="tz-subtitle"> Evaluacion Descriptiva </flux:subheading>

            {{-- Fila 1: intensidad(es) --}}
            <div class="flex flex-col gap-4 p-0 w-full">
                <flux:label> Intensidad </flux:label>
                <div class="tz-card-section flex flex-col gap-2 mx-2">
                    @foreach($axes as $axisIntensity)
                    <x-layouts::evaluations.partials.descriptive-intensity :axis="$axisIntensity" />
                    @endforeach
                </div>
            </div>

            {{-- Fila 2: selector de sabores — PENDIENTE de diseñar --}}
            @if($showFlavors)
            <div class="flex flex-col gap-4 w-full">
                <flux:label>{{$axis == 'overall' ? 'Defectos' : 'Atributos cata'}}</flux:label>
                <div class="tz-card-section p-4 mx-2">
                    <x-layouts::evaluations.partials.descriptor-cascade :nodes="$cataNodes" :target="$cataTarget" />
                </div>
            </div>
            @endif

            {{-- Fila 3: notas --}}

            <div x-data="{open: {{ !empty($descriptive['note'][$axis]) ? 'true' : 'false' }}}" class="felx flex-col">
                <flux:link x-show="!open" @click="open = true" class="tz-paragraph p-2">
                    + Agregar nota
                </flux:link>

                {{--Nota oculta--}}
                <div x-show="open" x-cloak class="flex flex-col gap-2">
                    <flux:label class="pb-2">Notas: </flux:label>
                    <flux:textarea wire:model="descriptive.note.{{ $axis }}"
                        placeholder="Observaciones descriptivas..." rows="3" />
                    <flux:link type="button" @click="open = false; $wire.set('descriptive.note.{{ $axis }}', null)"
                        class="text-sm self-end" style="color: var(--color-oxblood)">
                        Eliminar nota
                    </flux:link>
                </div>
            </div>
        </div>
        @endif

        {{-- Seccion Affective --}}
        <div class="flex-1 min-w-0 flex flex-col items-stretch p-2 gap-4">
            <flux:subheading class="tz-subtitle"> Evaluacion Afectiva </flux:subheading>

            {{-- Fila 1: escala(s) afectiva(s) --}}
            <div class="flex flex-col gap-4 w-full">
                @if($axis!=='overall')
                <flux:label>Evaluacion Afectiva</flux:label>
                @endif
                <div class="tz-card-section flex flex-col gap-2 mx-2">
                    @foreach($axes as $axisAffective)
                    <x-layouts::evaluations.partials.affective-bean :axis="$axisAffective" />
                    @endforeach
                </div>
                @if($axis=='overall')
                <div x-data="{open: false}" class="flex flex-col gap-4 p-0 w-full">
                    <div class="inline-flex gap-2 mb-2">
                        <flux:label> Taza con defectos </flux:label>
                        <flux:checkbox
                            wire:model="affective.is_defective"
                            :value="true"
                            @click="open = !open">
                        </flux:checkbox>
                    </div>

                    <div x-show="open" x-cloak class="flex flex-col gap-4 w-full">
                        <flux:label>{{$axis == 'overall' ? 'Defectos' : 'Atributos cata'}}</flux:label>
                        <div class="tz-card-section p-4 mx-2">
                            <x-layouts::evaluations.partials.descriptor-cascade :nodes="$cataNodes" :target="$cataTarget" />
                        </div>
                    </div>
                </div>
                @endif
                <div class="flex flex-col p-2">
                    <flux:label class="pb-2">Notas: </flux:label>
                    <flux:textarea wire:model="{{$axis == 'overall' ? 'note':'affective.note.' . $axis}}"
                        placeholder="Observaciones descriptivas..." rows="3" />
                </div>
            </div>
        </div>
    </div>
</div>