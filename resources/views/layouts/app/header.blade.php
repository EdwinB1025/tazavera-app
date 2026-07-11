<flux:header class="flex max-md:flex-col max-md:!gap-5 items-center justify-between">

    <div class="flex items-center justify-center xl:mr-20 gap-1 lg:w-26 xl:w-42 cursor-pointer">
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

    <flux:spacer />

    <x-user.desktop-user-menu class="flex items-stretch justify-center text-center w-16 xl:w-46 max-md:hidden" :full="false" />
</flux:header>