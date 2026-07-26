<x-layouts::app :title="__('Evaluaciones')">
    <div class="flex flex-col gap-4 mx-[5%] my-[2.5%]">
        <div class="flex flex-col gap-0 mb-2">
            <flux:subheading>EVALUACIONES</flux:subheading>
        </div>

        @include('layouts::evaluations.partials.evaluation-list')
    </div>

</x-layouts::app>