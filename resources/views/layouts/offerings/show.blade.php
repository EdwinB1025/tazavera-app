<x-layouts::app :title="ucfirst($offering->coffee->name)">
    <div class="flex flex-col gap-4 mx-[5%] my-[2.5%]">
        <flux:link :href="route('offerings', request()->query())" class="mb-2">
            <flux:icon.arrow-left class="inline size-4" /> Volver
        </flux:link>

        <div class="tz-form-main flex flex-col gap-2 mb-4">
            <div class="flex items-start justify-between gap-2">
                <div class="flex max-md:flex-col gap-2 items-center">
                    @if($offering->verification_status === 'verified')
                    <flux:badge icon="check" class="tz-badge-verified">Verificado</flux:badge>
                    @else
                    <flux:badge icon="clock" class="tz-badge-provisional">Provisional</flux:badge>
                    @endif
                    <flux:heading level="2">{{ ucfirst($offering->coffee->name) }}</flux:heading>
                </div>
                <flux:badge>{{ $offering->concordance ?? 'N/A' }}</flux:badge>
            </div>

            <flux:text class="!text-sm">
                <span class="tz-offering-label">Cafetería:</span>
                <flux:link href="">{{ $offering->location->name }}</flux:link>
                · <span class="tz-offering-label">Tostadora:</span> {{ $offering->coffee->roastery ?? 'N/A' }}
            </flux:text>
        </div>

        <div x-data="{ tab: '{{ request('tab', 'general') }}' }"
            x-init="$watch('tab', value => {
                const url = new URL(window.location);
                url.searchParams.set('tab', value);
                window.history.replaceState({}, '', url);
            })">
            <div class="tz-evaluations-nav mx-[5%]">
                <nav class="flex items-stretch flex-1" data-flux-tabs>
                    <button type="button" @click="tab = 'general'" :data-current="tab === 'general'" data-flux-navbar-items>
                        <div class="text-sm font-medium leading-none whitespace-nowrap" data-content>Información General</div>
                    </button>
                    <button type="button" @click="tab = 'evaluaciones'" :data-current="tab === 'evaluaciones'" data-flux-navbar-items>
                        <div class="text-sm font-medium leading-none whitespace-nowrap" data-content>Evaluaciones</div>
                    </button>
                    <button type="button" @click="tab = 'tecnica'" :data-current="tab === 'tecnica'" data-flux-navbar-items>
                        <div class="text-sm font-medium leading-none whitespace-nowrap" data-content>Información Técnica</div>
                    </button>
                </nav>
            </div>

            <div x-show="tab === 'general'" class="mt-4">
                <x-layouts::offerings.partials.general-info :offering="$offering" />
            </div>
            <div x-show="tab === 'evaluaciones'" x-cloak class="mt-4 flex flex-col gap-18 mx-[2.5%]">
                <x-layouts::offerings.partials.consensus :offering="$offering" />
                <div class="shadow-xl rounded-2xl p-2 bg-[color-mix(in_srgb,var(--color-neutral)_95%,black)]">
                    @include('layouts::evaluations.partials.evaluation-list')
                </div>
            </div>
            <div x-show="tab === 'tecnica'" x-cloak class="mt-4">
                <x-layouts::offerings.partials.technical-info :offering="$offering" />
            </div>
        </div>
    </div>
</x-layouts::app>