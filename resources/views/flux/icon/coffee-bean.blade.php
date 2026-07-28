@props([
'number' => null,
'size' => 'md',
'fill' => 'var(--color-brown-900)',
'bg' => 'var(--color-ui-button-light)'
])

@php
$sizes = [
'sm' => 'w-4 h-4',
'md' => 'w-6 h-6',
'lg' => 'w-8 h-8',
'xl' => 'w-10 h-10 max-md:w-8 max-md:h-8'
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => 'relative inline-flex items-center justify-center ' . $sizeClass]) }}>
    <svg fill="{{$fill}}" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://w3.org" class="w-full h-full" stroke="{{$fill}}" stroke-width="1.4">

        <g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(5.16,5.16), scale(0.58)">
            <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="14.4" fill="{{$bg}}" stroke-width="0" />
        </g>

        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.08" />

        <g id="SVGRepo_iconCarrier">
            <g id="Coffee_Bean" data-name="Coffee Bean">
                <path d="M19.151,4.868a6.744,6.744,0,0,0-5.96-1.69,12.009,12.009,0,0,0-6.54,3.47,11.988,11.988,0,0,0-3.48,6.55,6.744,6.744,0,0,0,1.69,5.95,6.406,6.406,0,0,0,4.63,1.78,11.511,11.511,0,0,0,7.87-3.56C21.3,13.428,22.1,7.818,19.151,4.868Zm-14.99,8.48a11.041,11.041,0,0,1,3.19-5.99,10.976,10.976,0,0,1,5.99-3.19,8.016,8.016,0,0,1,1.18-.09,5.412,5.412,0,0,1,3.92,1.49.689.689,0,0,1,.11.13,6.542,6.542,0,0,1-2.12,1.23,7.666,7.666,0,0,0-2.96,1.93,7.666,7.666,0,0,0-1.93,2.96,6.589,6.589,0,0,1-1.71,2.63,6.7,6.7,0,0,1-2.63,1.71,7.478,7.478,0,0,0-2.35,1.36A6.18,6.18,0,0,1,4.161,13.348Zm12.49,3.31c-3.55,3.55-8.52,4.35-11.08,1.79a1.538,1.538,0,0,1-.12-.13,6.677,6.677,0,0,1,2.13-1.23,7.862,7.862,0,0,0,2.96-1.93,7.738,7.738,0,0,0,1.93-2.96,6.589,6.589,0,0,1,1.71-2.63,6.589,6.589,0,0,1,2.63-1.71,7.6,7.6,0,0,0,2.34-1.37C20.791,9.2,19.821,13.488,16.651,16.658Z" />
            </g>
        </g>

    </svg>

    @if($number !== null)
    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold leading-none [-webkit-text-stroke:0.5px_black]" style="color: white">
        {{ $number }}
    </span>
    @endif
</span>