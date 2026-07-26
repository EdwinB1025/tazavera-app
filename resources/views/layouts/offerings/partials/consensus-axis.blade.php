@props(['axis', 'value'])

@php
$full = (int) floor($value);
$fraction = $value - $full;
@endphp

<div class="flex flex-col items-center p-2 w-full">
    <div class="flex items-center justify-between gap-2 w-full">
        <flux:label class="self-start w-20 shrink-0">{{ __('axis.'.$axis) }}: </flux:label>
        <div class="flex flex-nowrap gap-1">
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
                <span @if($class) class="{{ $class }}" @endif @if($style) style="{{ $style }}" @endif>
                    <flux:icon.coffee-bean :number="$i" size="lg" bg="currentColor" />
                </span>
                @endfor
        </div>
        <span class="tz-paragraph italic underline w-12 shrink-0 text-right">{{ $value }}</span>
    </div>
</div>