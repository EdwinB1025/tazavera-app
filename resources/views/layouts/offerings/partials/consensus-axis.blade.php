@props(['axis', 'value'])

@php
$full = (int) floor($value);
$fraction = $value - $full;
@endphp

<div class="flex flex-col items-center min-w-0 p-2 w-full">
    <div class="grid grid-cols-[1fr_auto_0.5fr] items-center gap-2 min-w-0 w-full">
        <flux:label class="justify-self-start">{{ __('axis.'.$axis) }}: </flux:label>
        <div class="flex flex-nowrap items-center justify-self-center min-w-0">
            @for($i = 1; $i <= 9; $i++)
                @php
                $class=null;
                $style=null;

                if ($i <=$full) {
                $class='bean-on' ;
                } elseif ($i===$full + 1 && $fraction> 0) {
                $style = 'color: color-mix(in srgb, '
                . 'var(--color-bean-on) '
                . (int) round($fraction * 100) . '%, var(--color-bean-off));';
                } else {
                $class = 'bean-off';
                }
                @endphp
                <span @if($class) class="{{ $class }} min-w-0" @endif @if($style) style="{{ $style }}" @endif>
                    <flux:icon.coffee-bean :number="$i" size="lg" bg="currentColor" />
                </span>
                @endfor
        </div>
        <span class="tz-paragraph italic underline w-auto justify-self-end">{{ $value }}</span>
    </div>
</div>