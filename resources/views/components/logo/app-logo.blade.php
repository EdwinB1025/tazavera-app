@props(['sidebar' => false, 'navbar' => false, 'iconClass' => ''])

@if($sidebar||$navbar)
<a href="{{ route('home') }}" {{ $attributes->class('flex items-center justify-center tz-brand gap-2')}}>
    <div class="flex aspect-square items-center justify-center shrink-0">
        <x-logo.icon class="{{ $iconClass }} fill-current -mt-1" />
    </div>
    <span class="flex tz-brand max-lg:hidden">TAZAVERA</span>
</a>
@else
<a href="{{ route('home') }}" {{ $attributes->class('flex flex-col items-center gap-2') }} wire:navigate>
    <span class="flex aspect-square items-center justify-center rounded-md">
        <x-logo.icon class="fill-current" />
    </span>
</a>
@endif