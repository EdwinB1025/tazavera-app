@props([
'title',
'axes' => [],
'open' => false,
'showFlavors' => true,
])

<div class="tz-form-main p-0" x-data="{ open: {{ $open ? 'true' : 'false' }} }">
    {{-- Encabezado con toggle --}}
    <div class="tz-card-section m-0 mb-3 !rounded-b-none p-2">
        <button type="button" @click="open = !open"
            class="group flex items-center justify-between gap-2 py-2 px-2 w-full text-left">
            <div class="flex items-center gap-3">
                <flux:subheading>{{ $title }}</flux:subheading>
            </div>
            <div class="flex items-center gap-2">
                <span class="opacity-60 text-xs px-2 py-0.5 rounded-full"
                    style="background: var(--color-nav-obscure); color: white">DESCRIPTIVO 0-15</span>
                <span class="opacity-60 text-xs px-2 py-0.5 rounded-full"
                    style="background: var(--color-ui-button-light); color: white">AFECTIVO 1-9</span>
                <span class="opacity-80 group-hover:opacity-100 transition-opacity"
                    style="color: var(--color-primary)">
                    <flux:icon.chevron-down x-show="!open" class="w-4 h-4" />
                    <flux:icon.chevron-up x-show="open" x-cloak class="w-4 h-4" />
                </span>

            </div>
        </button>
    </div>

    {{-- Contenido expandible --}}
    <div x-show="open" x-collapse class="grid grid-cols-2 gap-6 p-4">

        {{-- Fila 1: intensidad(es) | escala(s) afectiva(s) --}}
        <div class="flex flex-col gap-4 p-2">
            @foreach($axes as $axis)
            <x-layouts::evaluations.partials.descriptive-intensity :axis="$axis" label="Intensidad" />
            @endforeach
        </div>

        <div class="flex flex-col gap-4">
            <flux:label>Evaluacion Afectiva</flux:label>
            @foreach($axes as $axis)
            <x-layouts::evaluations.partials.affective-bean :axis="$axis" />
            @endforeach
        </div>

        {{-- Fila 2: selector de sabores — PENDIENTE de diseñar --}}
        @if($showFlavors)
        <div class="col-span-2">
            {{-- descriptores / sabores irán aquí --}}
        </div>
        @endif

        {{-- Fila 3: notas --}}
        <div class="col-span-2 flex flex-col gap-4">
            @foreach($axes as $axis)
            <div class="p-2">
                <flux:label class="pb-2">Notas {{ count($axes) > 1 ? ' '.ucfirst($axis).':' : ':' }}</flux:label>
                <flux:textarea wire:model="descriptive.axis.{{ $axis }}.note"
                    placeholder="Observaciones descriptivas..." rows="3" />
            </div>
            @endforeach
        </div>
    </div>
</div>