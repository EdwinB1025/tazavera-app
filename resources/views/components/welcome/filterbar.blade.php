<form class="tz-filter" method="GET" action="">
    <flux:field>
        <flux:label>Ciudad</flux:label>
        <flux:select placeholder="Todas">
            <flux:select.option value="">Todas</flux:select.option>
            <flux:select.option value="barcelona">Barcelona</flux:select.option>
            <flux:select.option value="madrid">Madrid</flux:select.option>
        </flux:select>
    </flux:field>

    {{-- ORIGEN --}}
    <flux:field>
        <flux:label>Origen</flux:label>
        <flux:select placeholder="Todos">
            <flux:select.option value="">Todos</flux:select.option>
            <flux:select.option value="colombia">Colombia</flux:select.option>
            <flux:select.option value="etiopia">Etiopía</flux:select.option>
            <flux:select.option value="kenia">Kenia</flux:select.option>
        </flux:select>
    </flux:field>

    {{-- PROCESO --}}
    <flux:field>
        <flux:label>Proceso</flux:label>
        <flux:select placeholder="Todos">
            <flux:select.option value="">Todos</flux:select.option>
            <flux:select.option value="lavado">Lavado</flux:select.option>
            <flux:select.option value="natural">Natural</flux:select.option>
            <flux:select.option value="honey">Honey</flux:select.option>
        </flux:select>
    </flux:field>

    {{-- PUNTAJE --}}
    <flux:field>
        <flux:label>Puntaje</flux:label>
        <flux:select placeholder="Cualquiera">
            <flux:select.option value="">Cualquiera</flux:select.option>
            <flux:select.option value="80">80+</flux:select.option>
            <flux:select.option value="85">85+</flux:select.option>
            <flux:select.option value="90">90+</flux:select.option>
        </flux:select>
    </flux:field>

    {{-- SABORES --}}
    <flux:field>
        <flux:label>Sabores</flux:label>
        <flux:input placeholder="frutal, chocolate..." icon="magnifying-glass" />
    </flux:field>

    {{-- BUSCAR --}}
    <div class="flex-[0.7] flex items-center justify-center self-stretch border-l border-l-[rgba(15,13,11,0.15)]">
        <flux:button variant="primary" icon="magnifying-glass">Buscar</flux:button>
    </div>

</form>