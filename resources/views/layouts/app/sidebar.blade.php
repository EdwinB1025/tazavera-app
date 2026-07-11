<flux:sidebar collapsible class="border-e">
    <flux:sidebar.header>
        <flux:sidebar.toggle icon="chevron-left" class="in-data-flux-sidebar-collapsed-desktop:hidden" />
        <flux:sidebar.toggle icon="chevron-right" class="not-in-data-flux-sidebar-collapsed-desktop:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group class="grid">
            <flux:sidebar.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                {{ __('Ofertas') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="clipboard-list" :current="request()->routeIs('evaluaciones')" wire:navigate>
                {{ __('Mis evaluaciones') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <div class="sm:hidden mt-auto mb-4">
        <x-user.desktop-user-menu :full="true" />
    </div>
</flux:sidebar>

<flux:sidebar.toggle icon="bars-2" inset="left" class="lg:hidden max-lg:ms-[10%] max-lg:mt-[5%]" />