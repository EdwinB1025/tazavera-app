<flux:header class="flex max-md:flex-col !px-4 items-center justify-between">

    <div class="flex items-center justify-start pr-6 md:mx-10 gap-1 md:w-39 cursor-pointer">
        <x-logo.app-logo :navbar="true" iconClass="size-10 max-md:size-18" href="{{ route('home') }}" wire:navigate />
    </div>

    <flux:navbar>
        <flux:navbar.item class="" icon="coffee" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
            {{ __('Explora Cafés') }}
        </flux:navbar.item>
        <flux:navbar.item class="" icon="map-pin" wire:navigate>
            {{ __('Cafeterias') }}
        </flux:navbar.item>
        <flux:navbar.item class="" icon="waves-vertical" wire:navigate>
            {{ __('Tostadores') }}
        </flux:navbar.item>
        <flux:navbar.item class="" icon="handshake" wire:navigate>
            {{ __('Foro') }}
        </flux:navbar.item>
    </flux:navbar>

    <x-user.desktop-user-menu class="flex items-stretch justify-center text-center pl-4 w-16 xl:w-46 max-md:hidden" :full="false" />
</flux:header>