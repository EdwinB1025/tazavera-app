{{-- BÚSQUEDA POR NOMBRE --}}
<flux:field class="!flex-[3]">
    <flux:label>Buscar Café</flux:label>
    <flux:input
        type="text"
        name="search"
        placeholder="Nombre del café..."
        icon="magnifying-glass"
        value="{{ request('search') }}" />
</flux:field>

{{-- BÚSQUEDA POR CIUDAD --}}

<flux:field>
    <flux:label>Ciudad</flux:label>
    <flux:select name="city" placeholder="Todas">
        <flux:select.option value="">Todas</flux:select.option>
        @foreach ($cities as $city)
        <flux:select.option value="{{ $city }}" :selected="request('city') === $city">{{ $city }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- ORIGEN --}}
<flux:field>
    <flux:label>Origen</flux:label>
    <flux:select name="origin" placeholder="Todos">
        <flux:select.option value="">Todos</flux:select.option>
        @foreach ($origins as $origin)
        <flux:select.option value="{{ $origin }}" :selected="request('origin') === $origin">{{ $origin }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- PROCESO --}}
<flux:field>
    <flux:label>Proceso</flux:label>
    <flux:select name="process" placeholder="Todos">
        <flux:select.option value="">Todos</flux:select.option>
        @foreach ($processes as $process)
        <flux:select.option value="{{ $process }}" :selected="request('process') === $process">{{ $process }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- PUNTAJE --}}
<flux:field>
    <flux:label>Puntaje</flux:label>
    <flux:select name="score" placeholder="Cualquiera">
        <flux:select.option value="">Cualquiera</flux:select.option>
        <flux:select.option value="80" :selected="request('score') === '80'">80+</flux:select.option>
        <flux:select.option value="85" :selected="request('score') === '85'">85+</flux:select.option>
        <flux:select.option value="90" :selected="request('score') === '90'">90+</flux:select.option>
    </flux:select>
</flux:field>

{{-- SABORES PRINCIPALES --}}
<flux:field>
    <flux:label>Sabores Principales</flux:label>
    <flux:select name="main_taste" placeholder="Todos">
        <flux:select.option value="">Todos</flux:select.option>
        @foreach ($main_tastes as $taste)
        <flux:select.option value="{{ $taste }}" :selected="request('main_taste') === $taste">{{ $taste }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- SABORES SECUNDARIOS --}}
<flux:field>
    <flux:label>Sabores Secundarios</flux:label>
    <flux:select name="specific_taste" placeholder="Todos">
        <flux:select.option value="">Todos</flux:select.option>
        @foreach ($specific_tastes as $taste)
        <flux:select.option value="{{ $taste }}" :selected="request('specific_taste') === $taste">{{ $taste }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>