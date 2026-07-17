<div>
    {{-- Desktop: bar --}}
    <div class="hidden lg:block">
        <form class="tz-filter" method="GET" action="{{ route('offerings') }}">
            @include('livewire.coffees.partials.filter-fields') {{-- include in this case better so the rendered variables applied --}}

            <div class="flex-[0.8] flex items-center justify-center self-stretch border-l border-l-[rgba(15,13,11,0.15)]">
                <flux:button variant="primary" type="submit" icon="magnifying-glass">Buscar</flux:button>
            </div>
        </form>
    </div>

    {{-- Mobile: toggle to enabled filter menu--}}
    <div class="lg:hidden">
        <flux:modal.trigger name="offering-filters">
            <flux:button variant="primary" icon="funnel">Filtros</flux:button>
        </flux:modal.trigger>

        <flux:modal name="offering-filters" class="tz-filter-stack">
            <form class="flex flex-col gap-4" method="GET" action="{{ route('offerings') }}">
                @include('livewire.coffees.partials.filter-fields')

                <flux:button variant="primary" type="submit" icon="magnifying-glass" class="w-full">Buscar</flux:button>
            </form>
        </flux:modal>
    </div>
</div>