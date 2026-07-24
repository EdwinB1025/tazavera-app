<x-layouts::app :title="__('Evaluaciones')">
    <div class="flex flex-col gap-0 mb-2">
        <flux:subheading>EVALUACIONES</flux:subheading>
    </div>

    <div class="mb-4">
        <livewire:evaluations.evaluation-filterbar class="mt-auto" />
    </div>

    <div class="overflow-y-auto h-[calc(100vh-16rem)] flex flex-col gap-2 pr-2 mx-[1%]">
        @forelse($evaluations as $evaluation)
        @include('layouts::evaluations.partials.evaluation-card')
        @empty
        <flux:text class="!italic !text-secondary">No hay evaluaciones registradas todavía.</flux:text>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $evaluations->links() }}
    </div>
</x-layouts::app>