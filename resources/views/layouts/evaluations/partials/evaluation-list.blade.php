<div class="flex flex-col gap-5">
    <livewire:evaluations.evaluation-filterbar class="mt-auto" />

    <div class="overflow-y-auto max-h-[calc(100vh-16rem)] flex flex-col gap-2 pr-2 mx-[1%]">
        @forelse($evaluations as $evaluation)
        @include('layouts::evaluations.partials.evaluation-card')
        @empty
        <flux:text class="!italic !text-secondary">No hay evaluaciones registradas todavía.</flux:text>
        @endforelse
    </div>
</div>

<div class="mt-4">
    {{ $evaluations->links() }}
</div>
