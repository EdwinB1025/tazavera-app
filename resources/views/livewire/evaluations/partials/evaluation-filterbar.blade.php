<div>
    {{-- Desktop: bar --}}
    <div class="hidden lg:block">
        @if ($errors->any())
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition
            class="mb-4 p-3 bg-red-50 border border-red-300 rounded-lg">
            <ul class="list-none">
                @foreach ($errors->all() as $error)
                <li class="text-red-600 text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form class="tz-filter" method="GET" action="{{ url()->current() }}">
            @if(request('tab'))
            <input type="hidden" name="tab" value="{{ request('tab') }}">
            @endif
            @include('livewire.evaluations.partials.filter-fields')

            <div class="flex-[0.8] flex items-center justify-center self-stretch border-l border-l-[rgba(15,13,11,0.15)]">
                <flux:button variant="primary" type="submit" icon="magnifying-glass">Buscar</flux:button>
            </div>
        </form>
    </div>

    {{-- Mobile: toggle to enabled filter menu--}}
    <div class="lg:hidden">
        @if ($errors->any())
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition
            class="mb-4 p-3 bg-red-50 border border-red-300 rounded-lg">
            <ul class="list-none">
                @foreach ($errors->all() as $error)
                <li class="text-red-600 text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <flux:modal.trigger name="evaluation-filters">
            <flux:button variant="primary" icon="funnel">Filtros</flux:button>
        </flux:modal.trigger>

        <flux:modal name="evaluation-filters" class="tz-filter-stack">
            <form class="flex flex-col gap-4" method="GET" action="{{ url()->current() }}">
            @if(request('tab'))
            <input type="hidden" name="tab" value="{{ request('tab') }}">
            @endif
                @include('livewire.evaluations.partials.filter-fields')

                <flux:button variant="primary" type="submit" icon="magnifying-glass" class="w-full">Buscar</flux:button>
            </form>
        </flux:modal>
    </div>
</div>