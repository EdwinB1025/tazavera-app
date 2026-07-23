<x-layouts::app :title="__('Evaluaciones')">
    <div class="flex flex-col gap-0 mb-2">
        <flux:subheading>EVALUACIONES</flux:subheading>
    </div>

    <div class="overflow-y-auto h-[calc(100vh-16rem)] flex flex-col gap-2 pr-2 mx-[1%]">
    </div>

    <div class="mt-4">
        {{ $evaluations->links() }}
    </div>
</x-layouts::app>