@props(['axis'])
<div x-data="{ value: @entangle('descriptive.axis.'.$axis.'.value') }"
    x-init="value = value ?? 0"
    class="py-4 px-2 mx-2">

    <div class="flex items-center justify-between mb-1">
        <flux:subheading class="self-start">{{ucfirst($axis)}}: </flux:subheading>
        <span>
            <span class="tz-paragraph italic underline" x-text="value??0"></span><span class="tz-paragraph italic opacity-80">/15</span>
        </span>
    </div>

    <div class="flex p-2">
        <input type="range" min="0" max="15" step="1"
            x-model.number="value"
            :style="{ '--fill': (value / 15 * 100) + '%' }"
            class="tz-intensity-slider mx-4 w-full" />
    </div>

    <div class="flex justify-between text-sm font-bold leading-none mt-1 mb-2 mx-4"
        style="color: var(--color-text-primary); -webkit-text-stroke: 0.25px black">
        <span>0</span><span>5</span><span>10</span><span>15</span>
    </div>
    <div class=" flex justify-between font-semibold text-xs mx-4" style="color: var(--color-text-filter-label)">
        <span>BAJA</span><span>MEDIA</span><span>ALTA</span>
    </div>
</div>