@props(['cataFreq'])

@php
$cata = collect($cataFreq);
$refs = $cata->pluck('ref')->map(fn($r) => (int) $r)->all();
$labels = \App\Models\OlfactoryTaxonomy::byRefs($refs)->get(['id', 'name_es', 'color'])->keyBy('id');

$roots = $cata->where('level', 0);
@endphp

<div class="flex flex-col gap-6 w-full">
    @forelse($roots as $root)
    @php
    $rootLabel = $labels->get((int) $root['ref']);
    $l1Items = $cata->where('level', 1)->where('parent_id', $root['ref']);
    @endphp

    <div class="flex flex-col gap-4">
        <span style="background:{{ $rootLabel->color }}; color:#fff"
            class="px-3 py-1 rounded-full text-sm w-fit">
            {{ $rootLabel->name_es }}
            @if(isset($root['count']))
            <span class="opacity-80">· {{ $root['count'] }} catas</span>
            @endif
        </span>

        <div class="ml-5 flex flex-col gap-2 border-l pl-3" style="border-color: var(--color-ui-button-light)">
            @foreach($l1Items as $l1)
            @php
            $l1Label = $labels->get((int) $l1['ref']);
            $l2Items = $cata->where('level', 2)->where('parent_id', $l1['ref']);
            @endphp
            <div class="flex flex-col lg:flex-row lg:items-center gap-1.5">
                <span style="background:{{ $l1Label->color }}; color:#fff"
                    class="px-2.5 py-0.5 rounded-full text-xs w-fit">
                    {{ $l1Label->name_es }}
                    @if(isset($l1['count']))
                    <span class="opacity-80">· {{ $l1['count'] }} catas</span>
                    @endif
                </span>

                @if($l2Items->count())
                <span class="w-full h-px lg:w-px lg:h-4 lg:self-stretch" style="background-color: var(--color-ui-button-light)"></span>

                <div class="ml-5 lg:ml-0 flex flex-wrap gap-1.5">
                    @foreach($l2Items as $l2)
                    @php $l2Label = $labels->get((int) $l2['ref']); @endphp
                    <span style="background:{{ $l2Label->color }}; color:#fff"
                        class="px-2.5 py-0.5 rounded-full text-xs w-fit">
                        {{ $l2Label->name_es }}
                        @if(isset($l2['count']))
                        <span class="opacity-80">· {{ $l2['count'] }} catas</span>
                        @endif
                    </span>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <flux:text class="!italic !text-secondary">Sin datos de cata disponibles.</flux:text>
    @endforelse
</div>