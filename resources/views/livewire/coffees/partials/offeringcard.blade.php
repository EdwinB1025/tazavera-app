<?php

use Livewire\Component;
use App\Models\OlfactoryTaxonomy;

new class extends Component
{
    //
};
?>

<div class="tz-offering-card mb-2" data-flux-offering-card>
    <div class="flex max-lg:flex-col">
        <div class="flex flex-1 min-w-0">
            {{-- Lado izquierdo: puntaje --}}
            <div class="tz-right-border-card" data-flux-offering-score>
                @php
                $score = $offering->getScore() ?? 0;

                if ($score >= 90) {
                $badge_label = 'Excepcional';
                $badge_color = 'tz-rating-exceptional';
                } elseif ($score >= 85) {
                $badge_label = 'Excelente';
                $badge_color = 'tz-rating-excellent';
                } elseif ($score >= 80) {
                $badge_label = 'Bueno';
                $badge_color = 'tz-rating-good';
                } else {
                $badge_label = 'Comercial';
                $badge_color = 'tz-rating-commercial';
                }
                @endphp
                <div data-flux-offering-value>{{ $score }}</div>
                <div data-flux-offering-scale>/100</div>
                <flux:badge class="{{ $badge_color }}">{{$badge_label}}</flux:badge>
            </div>

            {{-- Lado derecho: contenido --}}
            <div data-flux-offering-content>
                {{-- Header: nombre + badges de estado --}}
                <div class="flex max-md:flex-col gap-2 items-center">
                    @if($offering->verification_status === 'verified')
                    <flux:badge icon="check" class="tz-badge-verified">Verificado</flux:badge>
                    @else
                    <flux:badge icon="clock" class="tz-badge-provisional">Provisional</flux:badge>
                    @endif
                    <flux:heading level="3">{{ ucfirst($offering->coffee->name) }}</flux:heading>
                </div>

                {{-- Cafetería --}}
                <div data-flux-offering-shop>
                    <span class="tz-offering-label text-sm max-md:text-xs">Cafetería:</span>
                    <flux:link href="" class="!text-sm max-md:!text-xs">
                        {{ $offering->location->name }}
                    </flux:link>
                </div>

                {{-- Tostadora --}}
                <div data-flux-offering-shop>
                    <span class="tz-offering-label text-sm max-md:text-xs">Tostadora:</span>
                    <flux:link href="" class="!text-sm max-md:!text-xs">
                        {{ $offering->coffee->roastery ?? 'N/A' }}
                    </flux:link>
                </div>

                {{-- Ubicación + proceso + variedad --}}
                <flux:text class="!text-xs !text-secondary max-md:text-xs! !italic">
                    {{ $offering->coffee->extrinsics['origin']['country'] ?? 'N/A' }}
                    . {{ $offering->coffee->extrinsics['origin']['region'] ?? 'N/A' }}
                    · {{ $offering->coffee->extrinsics['process'] ?? 'N/A' }}
                    · {{ $offering->coffee->extrinsics['variety'] ?? 'N/A' }}
                </flux:text>

                <div class="flex flex-col gap-1">
                    {{-- Olfativos: Base / Primarios / Secundarios (niveles 0-2), opacidad decreciente entre 100% y 85% --}}
                    @for ($level = 0; $level <= 2; $level++)
                        @php
                        $cata=$offering->getCata($level);
                        @endphp
                        @continue(empty($cata))
                        @php
                        $refs = array_column($cata, 'ref');
                        $taxonomies = OlfactoryTaxonomy::byRefs($refs)->get()->keyBy('id');
                        $opacity = sprintf('%02x', round((100 - $level * (100 - 85) / 2) / 100 * 255));
                        @endphp
                        <div data-flux-offering-tastes>
                            @foreach($cata as $item)
                            @php $taxonomy = $taxonomies[$item['ref']] ?? null; @endphp
                            <flux:badge class="!text-xs" :style="'background-color: ' . ($taxonomy->color ?? 'transparent') . $opacity">
                                {{ $taxonomy->name_es ?? 'N/A' }}
                            </flux:badge>
                            @endforeach
                        </div>
                        @endfor
                </div>
            </div>
        </div>

        {{-- Metadata: evaluaciones --}}
        <div class="flex flex-col pr-2 pt-2 max-md:mx-2 max-md:pr-0 max-lg:flex-row max-lg:tz-top-border-card justify-between">
            <div data-flux-offering-meta class="flex flex-col items-end max-lg:items-start gap-0">
                <span class="tz-offering-eval-count">{{ $offering->evaluation_count ?? 0 }} eval.</span>
                <span class="tz-offering-defective" data-flux-icon="alert-circle">
                    {{ $offering->defective_evaluation_count }} defectuosa
                </span>
            </div>
            {{-- Botones --}}
            <div class="flex" data-flux-offering-actions>
                <flux:button variant="outline" size="sm" icon="eye" href="">Ver</flux:button>
                <flux:button variant="outline" size="sm" icon="plus" href="{{route('evaluations.create', array_merge(request()->query(), ['offering' => $offering->id]))}}">Evaluar</flux:button>
            </div>
        </div>
    </div>

</div>