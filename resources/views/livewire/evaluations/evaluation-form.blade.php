<div class="flex flex-col gap-4">
    {{-- ENCABEZADO — CONTEXTO --}}
    <div class="tz-form-main flex flex-col gap-4">
        {{-- Método de extracción --}}
        <flux:field>
            <flux:label>Método de extracción</flux:label>
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
                <flux:subheading>Impresión de calidad</flux:subheading>
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

    <x-layouts::evaluations.partials.evaluation-card title="Aroma" :axes="['aroma']" /> {{-- con sabores (default) --}}
    <x-layouts::evaluations.partials.evaluation-card title="Dulzor" :axes="['sweetness']" :show-flavors="false" /> {{-- sin sabores --}}
</div>