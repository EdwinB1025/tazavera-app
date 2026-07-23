{{-- CAFÉ --}}
<flux:field>
    <flux:label>Café</flux:label>
    <flux:select name="coffee_id" placeholder="Todos">
        <flux:select.option value="">Todos</flux:select.option>
        @foreach ($coffees as $coffee)
        <flux:select.option value="{{ $coffee->id }}" :selected="old('coffee_id', request('coffee_id')) == $coffee->id">{{ $coffee->name }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- CAFETERÍA --}}
<flux:field>
    <flux:label>Cafetería</flux:label>
    <flux:select name="location_id" placeholder="Todas">
        <flux:select.option value="">Todas</flux:select.option>
        @foreach ($locations as $location)
        <flux:select.option value="{{ $location->id }}" :selected="old('location_id', request('location_id')) == $location->id">{{ $location->name }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

{{-- CIUDAD --}}
<flux:field>
    <flux:label>Ciudad</flux:label>
    <flux:select name="city" placeholder="Todas">
        <flux:select.option value="">Todas</flux:select.option>
        @foreach ($cities as $city)
        <flux:select.option value="{{ $city }}" :selected="old('city', request('city')) === $city">{{ $city }}</flux:select.option>
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