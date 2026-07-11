<div class="tz-bg-gray flex items-start rounded-xl max-md:flex-col ring-1 ring-black/10 overflow-hidden">
    <div class="w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Settings') }}">
            <flux:navlist.item :href="route('profile.edit')" wire:navigate>{{ __('Perfil') }}</flux:navlist.item>
            <flux:navlist.item :href="route('security.edit')" wire:navigate>{{ __('Seguridad') }}</flux:navlist.item>
            <flux:navlist.item :href="route('appearance.edit')" wire:navigate>{{ __('Apariencia') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="tz-bg-form-muted flex-1 self-stretch max-md:pt-6 p-3">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="tz-bg-form-muted p-3">
            {{ $slot }}
        </div>
    </div>
</div>