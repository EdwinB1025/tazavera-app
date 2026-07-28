<x-layouts::app :title="__('Evaluación')">
    <div class="flex flex-col gap-4 mx-[5%] my-[2.5%]">
        <flux:link :href="request('back', route('evaluations'))" class="mb-2">
            <flux:icon.arrow-left class="inline size-4" /> Volver
        </flux:link>

        <flux:heading level="2">{{ ucfirst($evaluation->offering->coffee->name) }}</flux:heading>

        <x-layouts::evaluations.partials.technical-info :evaluation="$evaluation" />

        <div data-flux-evaluation-actions>
            <flux:button variant="outline" size="sm" data-tz-action="cancel" href="{{ request('back', route('evaluations')) }}">Volver</flux:button>
        </div>
    </div>
</x-layouts::app>
