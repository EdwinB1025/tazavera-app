<div class="flex flex-col gap-4">
    {{-- ENCABEZADO --}}

    <div class="flex flex-col items-start gap-4 mb-2">
        <div class="flex items-center justify-between w-full" data-flux-evaluation-actions>
            <flux:link :href="route('offerings', request()->query())">
                <flux:icon.arrow-left class="inline size-4" /> Volver
            </flux:link>
            <div class="flex items-center gap-2">
                <flux:button variant="outline" size="sm" data-tz-action="save" wire:click="saveDraft">Guardar</flux:button>
                <flux:button variant="outline" size="sm" data-tz-action="close" wire:click="closeEvaluation">Cerrar Evaluación</flux:button>
            </div>
        </div>
        <flux:subheading>EVALUACIÓN</flux:subheading>
        <div class="flex flex-col gap-0">
            <flux:heading class="justify-self-start">{{ucfirst($offering->coffee->name)}}</flux:heading>
            <flux:text>
                <flux:link href="#"> {{$offering->location->name}} </flux:link>
            </flux:text>
        </div>


    </div>

    {{-- ENCABEZADO — CONTEXTO --}}
    <div class="tz-form-main flex flex-col gap-4">
        {{-- Método de extracción --}}
        <flux:field>
            <div class="flex items-center justify-between mb-2">
                <flux:label>Método de extracción</flux:label>
                <div class="flex gap-4 mr-6 mb-2">
                    <span class="opacity-60 text-xs px-2 py-0.5 rounded-full"
                        style="background: var(--color-nav-obscure); color: white">ESCALA DESCRIPTIVA: 0-15</span>
                    <span class="opacity-60 text-xs px-2 py-0.5 rounded-full"
                        style="background: var(--color-ui-button-light); color: white">ESCALA AFECTIVA: 1-9</span>
                </div>
            </div>
            <flux:select wire:model="extraction_method" placeholder="V60, Espresso, Chemex..." data-flux-input>
                @foreach($this::EXTRACTION_METHODS as $method)
                <flux:select.option value="{{ $method }}">
                    {{ ucwords(str_replace('_', ' ', $method)) }}
                </flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>

        {{-- Leyenda: Impresión de calidad (1–9) --}}
        <div class="tz-card-section" x-data="{ open: false }">
            <button type="button" @click="open = !open"
                class="group flex items-center justify-between gap-2 py-2 px-2 w-full text-left">
                <flux:subheading>Impresión de calidad afectiva</flux:subheading>
                <span class="opacity-0.8 group-hover:opacity-100 transition-opacity"
                    style="color: var(--color-brown-800)">
                    <flux:icon.chevron-down x-show="!open" class="w-4 h-4" />
                    <flux:icon.chevron-up x-show="open" x-cloak class="w-4 h-4" />
                </span>
            </button>

            <div x-show="open" x-collapse
                class="tz-top-border-card grid grid-cols-3 gap-x-6 gap-y-1 p-2 text-sm">
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="1" size="md" /> · Extremadamente baja</span>
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="2" size="md" /> · Ligeramente baja</span>
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="3" size="md" /> · Moderadamente alta</span>
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="4" size="md" /> · Muy baja</span>
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="5" size="md" /> · Ni alta ni baja</span>
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="6" size="md" /> · Muy alta</span>
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="7" size="md" /> · Moderadamente baja</span>
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="8" size="md" /> · Ligeramente alta</span>
                <span class="flex items-center tz-paragraph"><flux:icon.coffee-bean number="9" size="md" /> · Extremadamente alta</span>
            </div>
        </div>
    </div>

    @foreach($components as $item)
    @php
    $item = (object) $item;
    @endphp
    @include('layouts::evaluations.partials.evaluation-card')

    @endforeach

    {{-- ACCIONES FINALES --}}
    <div class="tz-top-border-card pt-3 mx-4" data-flux-evaluation-actions>
        <flux:button variant="outline" size="sm" data-tz-action="cancel" href="{{ route('offerings', request()->query()) }}">Cancelar</flux:button>
        <flux:button variant="outline" size="sm" data-tz-action="save" wire:click="saveDraft">Guardar</flux:button>
        <flux:button variant="outline" size="sm" data-tz-action="close" wire:click="closeEvaluation">Cerrar Evaluación</flux:button>
    </div>
</div>