@props(['axis', 'label' => null])

<div x-data="{ value: @entangle('descriptive.axis.'.$axis.'.value') }"
    x-init="value = value ?? 0">
    <div class="flex items-center justify-between mb-1">
        <flux:label>
            {{ $label ?? ucfirst($axis) }}
        </flux:label>
        <span>
            <span class="tz-paragraph italic underline" x-text="value??0"></span><span class="tz-paragraph italic opacity-80">/15</span>
        </span>
    </div>

    <div class="p-2">
        <input type="range" min="0" max="15" step="1"
            x-model.number="value"
            :style="{ '--fill': (value / 15 * 100) + '%' }"
            class="tz-intensity-slider w-full" />
    </div>

    <div class="flex justify-between text-sm font-bold leading-none mt-1 mb-2"
        style="color: var(--color-text-primary); -webkit-text-stroke: 0.25px black">
        <span>0</span><span>5</span><span>10</span><span>15</span>
    </div>
    <div class=" flex justify-between font-semibold text-xs" style="color: var(--color-text-filter-label)">
        <span>BAJA</span><span>MEDIA</span><span>ALTA</span>
    </div>
</div>