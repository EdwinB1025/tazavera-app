<header
    class="flex items-center justify-between w-full h-42 px-6"
    style="background-color: var(--color-nav-surface); height: 4rem;">

    <div class="flex items-center">
        <x-app-logo-icon class="w-16 h-16 -mt-1" />
        <span class="tz-brand">TAZAVERA</span>
    </div>

    <flux:navbar class="flex-1 justify-center">
        <flux:navbar.item href="#">Explora cafeterias</flux:navbar.item>
        <flux:navbar.item href="#">Lo mas valorado</flux:navbar.item>
        <flux:navbar.item href="#">Conocenos</flux:navbar.item>
    </flux:navbar>


    <div class="flex items-center text-center">
        <a class="tz-button-nav" href="{{ route('login') }}">Sign In</a>
    </div>
</header>