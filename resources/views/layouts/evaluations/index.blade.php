<x-layouts::app :title="__('Ofertas')">
    <div class="flex flex-col gap-0 mb-2">
        <flux:link :href="route('offerings', request()->query())" class="mb-4">
            <flux:icon.arrow-left class="inline size-4" /> Volver
        </flux:link>
        <flux:subheading>EVALUACIÓN</flux:subheading>
        <flux:heading class="justify-self-start">{{$offering->coffee->name}}</flux:heading>
        <flux:text>
            <flux:link href="#"> {{$offering->location->name}} </flux:link>
        </flux:text>
    </div>

    <div class="overflow-y-auto flex flex-col gap-2 pr-2 mx-0">
        <livewire:evaluations.evaluation-form :offering="$offering" />
    </div>


</x-layouts::app>