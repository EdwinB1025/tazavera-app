{{-- BÚSQUEDA POR NOMBRE --}}
<flux:field class="!flex-[1.5]">
    <flux:label>Buscar Café</flux:label>
    <flux:input
        type="text"
        name="name"
        placeholder="Nombre del café..."
        icon="magnifying-glass"
        value="{{ old('name', request('name')) }}" />
</flux:field>

{{-- BÚSQUEDA POR CIUDAD --}}

<flux:field>
    <flux:label>Ciudad</flux:label>
    <flux:select name="city" placeholder="Todas">
        <flux:select.option value="">Todas</flux:select.option>
        @foreach ($cities as $city)
        <flux:select.option value="{{ $city }}" :selected="old('city', request('city')) === $city">{{ $city }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- ORIGEN --}}
<flux:field>
    <flux:label>Origen</flux:label>
    <flux:select name="origin" placeholder="Todos">
        <flux:select.option value="">Todos</flux:select.option>
        @foreach ($origins as $origin)
        <flux:select.option value="{{ $origin }}" :selected="old('origin', request('origin')) === $origin">{{ $origin }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- PROCESO --}}
<flux:field>
    <flux:label>Proceso</flux:label>
    <flux:select name="process" placeholder="Todos">
        <flux:select.option value="">Todos</flux:select.option>
        @foreach ($processes as $process)
        <flux:select.option value="{{ $process }}" :selected="old('process', request('process')) === $process">{{ $process }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- PUNTAJE --}}
<flux:field>
    <flux:label>Puntaje</flux:label>
    <flux:select name="score" placeholder="Cualquiera">
        <flux:select.option value="">Cualquiera</flux:select.option>
        <flux:select.option value="80" :selected="old('score', request('score')) === '80'">80+</flux:select.option>
        <flux:select.option value="85" :selected="old('score', request('score')) === '85'">85+</flux:select.option>
        <flux:select.option value="90" :selected="old('score', request('score')) === '90'">90+</flux:select.option>
    </flux:select>
</flux:field>

{{-- SABORES PRINCIPALES: usando alpine.js --}}
<flux:field class="!flex-[1.1]">
    <flux:label>Sabores</flux:label>
    <div x-data="{ open: false }" class="min-w-full relative group">
        <button
            type="button"
            @click="open = !open"
            x-ref="tastes_button"
            data-flux-control>
            <span class="flex justify-between items-start w-full">
                <span>Todos</span>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity">
                    <flux:icon.chevron-up-down x-show="!open" class="w-6 h-6" />
                    <flux:icon.chevron-up x-show="open" class="w-6 h-6" />
                </span>
            </span>
        </button>

        <div
            x-show="open"
            @click.outside="open = false"
            class="tz-filter-panel">
            @foreach ($main_tastes as $taste)
            <label>
                <input
                    type="checkbox"
                    name="main_tastes[]"
                    value="{{ $taste->id }}"
                    {{ in_array($taste->id, (array) old('main_tastes', request('main_tastes', []))) ? 'checked' : '' }} />
                <span>{{ $taste->name_es }}</span>
            </label>
            @endforeach
        </div>
    </div>
</flux:field>

{{-- SABORES SECUNDARIOS: mismo patrón --}}
<flux:field class="!flex-[1.1]">
    <flux:label>Olfativos</flux:label>
    <div x-data="{ open: false }" class="min-w-full relative group">
        <button
            type="button"
            @click="open = !open"
            x-ref="specific_tastes_button"
            data-flux-control>
            <span class="flex justify-between items-start">
                <span>Todos</span>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity">
                    <flux:icon.chevron-up-down x-show="!open" class="w-6 h-6" />
                    <flux:icon.chevron-up x-show="open" class="w-6 h-6" />
                </span>
            </span>
        </button>

        <div
            x-show="open"
            @click.outside="open = false"
            class="tz-filter-panel">
            @foreach ($specific_tastes as $taste)
            <label>
                <input
                    type="checkbox"
                    name="specific_tastes[]"
                    value="{{ $taste->id }}"
                    {{ in_array($taste->id, (array) old('specific_tastes', request('specific_tastes', []))) ? 'checked' : '' }} />
                <span>{{ $taste->name_es }}</span>
            </label>
            @endforeach
        </div>
    </div>
</flux:field>