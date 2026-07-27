@props(['axis', 'value'])

@php
$value = $value ?? 0;
@endphp

<div class="py-4 px-2 mx-2">
    <div class="flex items-center justify-between mb-1">
        <flux:subheading class="self-start">{{ ucfirst($axis) }}: </flux:subheading>
        <span>
            <span class="tz-paragraph italic underline">{{ $value }}</span><span class="tz-paragraph italic opacity-80">/15</span>
        </span>
    </div>

    <div class="flex p-2">
        <div class="tz-intensity-slider mx-4 w-full" style="--fill: {{ $value / 15 * 100 }}%"></div>
    </div>

    <div class="flex justify-between text-sm font-bold leading-none mt-1 mb-2 mx-4"
        style="color: var(--color-text-primary); -webkit-text-stroke: 0.25px black">
        <span>0</span><span>5</span><span>10</span><span>15</span>
    </div>
    <div class="flex justify-between font-semibold text-xs mx-4" style="color: var(--color-text-filter-label)">
        <span>BAJA</span><span>MEDIA</span><span>ALTA</span>
    </div>
</div>
