<header
    class="flex items-center justify-between w-full h-42 px-6"
    style="background-color: var(--color-nav-surface); height: 4rem;">

    <div class="flex items-center justify-center gap-1 w-3xs">
        <x-logo.app-logo :navbar="true" />
    </div>

    <flux:navbar class="flex-1 justify-center">
        <flux:navbar.item href="#">Explora cafeterias</flux:navbar.item>
        <flux:navbar.item href="#">Lo mas valorado</flux:navbar.item>
        <flux:navbar.item href="#">Conocenos</flux:navbar.item>
    </flux:navbar>


    <div class="flex items-stretch justify-center text-center w-3xs">
        <a class="tz-button-nav " href="{{ route('login') }}">Sign In</a>
    </div>
</header>