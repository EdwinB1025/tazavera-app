<x-layouts::app :title="__('Evaluación')">
    <div class="flex flex-col gap-4 mx-[5%] my-[2.5%]">
        <div class="overflow-y-auto flex flex-col gap-2 pr-2 mx-0">
            <livewire:evaluations.evaluation-form :offering="$offering" :evaluation="$evaluation ?? null" />
        </div>
    </div>
</x-layouts::app>