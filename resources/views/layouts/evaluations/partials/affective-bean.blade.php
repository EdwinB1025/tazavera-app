@props(['axis'])
<div class="flex flex-col items-center gap-2 py-2 px-4 mx-4">
    <flux:subheading class="self-start">{{ __('axis.'.$axis) }}: </flux:subheading>
    <div x-data="{ selected: @entangle('affective.axis.'.$axis) }" class="flex flex-wrap gap-1">
        @for($i = 1; $i <= 9; $i++)
            <button type="button"
            @click="selected = {{ $i }}"
            :class="selected >= {{ $i }} ? 'bean-on' : 'bean-off'"
            class="transition-colors">
            <flux:icon.coffee-bean :number="$i" size="xl" bg="currentColor" />
            </button>
            @endfor
    </div>
</div>