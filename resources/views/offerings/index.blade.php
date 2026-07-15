<x-layouts::app :title="__('Ofertas')">
    <flux:subheading>CAFÉS</flux:header>
        <flux:heading class="mb-6">Descubre Ofertas</flux:header>
            <livewire:coffees.offering-filterbar class="mt-auto" />
            <div>
                @php $offering = $offerings->first() @endphp
                @include('livewire.coffees.partials.offeringcard')
            </div>
</x-layouts::app>