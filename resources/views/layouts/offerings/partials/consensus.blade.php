@props(['offering'])

@php
$consensus = $offering->getConsensus();
$axisAvg = data_get($consensus, 'axis_avg', []);
$cuppingAvg = data_get($consensus, 'cupping_avg', 0);
$cataFreq = data_get($consensus, 'cata_freq', []);

if ($cuppingAvg >= 90) {
$badgeColor = 'tz-rating-exceptional';
} elseif ($cuppingAvg >= 85) {
$badgeColor = 'tz-rating-excellent';
} elseif ($cuppingAvg >= 80) {
$badgeColor = 'tz-rating-good';
} else {
$badgeColor = 'tz-rating-commercial';
}

$axes = ['aroma', 'flavor', 'acidity', 'sweetness', 'mouthfeel', 'overall'];
@endphp

<div class="tz-form-main flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <flux:subheading class="tz-subtitle2">CONSENSO</flux:subheading>
        <flux:badge class="{{ $badgeColor }}">
            <div data-flux-card-value>{{ $cuppingAvg }}</div>
            <div data-flux-card-scale>/100</div>
        </flux:badge>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="flex-1 flex flex-col gap-3 p-4 max-md:p-2 w-full">
            <flux:subheading class="tz-subtitle">Atributos SCA</flux:subheading>
            <div class="flex flex-col p-2  w-full">
                @foreach($axes as $axis)
                <x-layouts::offerings.partials.consensus-axis :axis="$axis" :value="$axisAvg[$axis] ?? 0" />
                @endforeach
            </div>
        </div>

        <div class="flex-1 flex flex-col gap-3 p-4 max-md:p-2 w-full tz-left-border-card">
            <flux:subheading class="tz-subtitle">Rueda SCA — Perfil de cata</flux:subheading>
            <div class=" pl-6 w-full flex-1">
                <x-layouts::offerings.partials.cata-wheel-static :cataFreq="$cataFreq" />
            </div>
        </div>
    </div>
</div>