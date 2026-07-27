<x-layouts::app :title="__('Ofertas')">
    <div class="flex flex-col gap-4 mx-[5%] my-[2.5%]">
        <div class="flex flex-col gap-0 mb-2">
            <flux:subheading>CAFÉS</flux:subheading>
            <flux:heading class="justify-self-start">Descubre Ofertas</flux:heading>
        </div>

        <div class="mb-4">
            <livewire:coffees.offering-filterbar class="mt-auto" />
        </div>

        <div class="overflow-y-auto h-[calc(100vh-16rem)] flex flex-col gap-2 pr-2 mx-[1%]">
            @foreach($offerings as $offering)
            @include('livewire.coffees.partials.offeringcard')
            @endforeach
        </div>

        <div class="mt-4">
            {{ $offerings->links() }}
        </div>
    </div>
</x-layouts::app>