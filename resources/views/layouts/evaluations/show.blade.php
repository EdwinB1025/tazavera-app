<x-layouts::app :title="__('Evaluación')">
    <div class="flex flex-col gap-4 mx-[5%] my-[2.5%]">
        <flux:link :href="request('back', route('evaluations'))" class="mb-2">
            <flux:icon.arrow-left class="inline size-4" /> Volver
        </flux:link>

        {{-- TODO: contenido --}}
    </div>
</x-layouts::app>
