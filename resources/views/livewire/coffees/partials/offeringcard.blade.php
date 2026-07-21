<?php

use Livewire\Component;
use App\Models\OlfactoryTaxonomy;

new class extends Component
{
    //
};
?>

<div class="tz-offering-card mb-2" data-flux-offering-card>
    <div class="flex">
        {{-- Lado izquierdo: puntaje --}}
        <div class="tz-right-border-card" data-flux-offering-score>
            @php
            $score = $offering->getScore() ?? 85.5;

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
            <div data-flux-offering-header>
                @if($offering->verification_status === 'verified')
                <flux:badge icon="check" class="tz-badge-verified">Verificado</flux:badge>
                @else
                <flux:badge icon="clock" class="tz-badge-provisional">Provisional</flux:badge>
                @endif
                <flux:heading level="3" class="!text-xl">{{ $offering->coffee->name }}</flux:heading>
            </div>

            {{-- Cafetería --}}
            <div data-flux-offering-shop>
                <span class="tz-offering-label">Cafetería:</span>
                <flux:link href="" class="!text-sm">
                    {{ $offering->location->name }}
                </flux:link>
            </div>

            {{-- Tostadora --}}
            <div data-flux-offering-shop>
                <span class="tz-offering-label">Tostadora:</span>
                <flux:link href="" class="!text-sm">
                    {{ $offering->coffee->roastery ?? 'N/A' }}
                </flux:link>
            </div>

            {{-- Ubicación + proceso + variedad --}}
            <flux:text class="!text-xs !text-secondary !italic">
                {{ $offering->coffee->extrinsics['origin']['country'] ?? 'N/A' }}
                . {{ $offering->coffee->extrinsics['origin']['region'] ?? 'N/A' }}
                · {{ $offering->coffee->extrinsics['process'] ?? 'N/A' }}
                · {{ $offering->coffee->extrinsics['variety'] ?? 'N/A' }}
            </flux:text>

            <div class="flex flex-col gap-1">
                {{-- Sabores Primarios --}}
                <div data-flux-offering-tastes>
                    @php
                    $primary = $offering->getTastes(0);
                    $primaryRefs = array_column($primary, 'ref');
                    $taxonomies = OlfactoryTaxonomy::byRefs($primaryRefs)->get()->keyBy('id');

                    @endphp
                    @foreach($primary as $cata)
                    @php $taxonomy = $taxonomies[$cata['ref']] ?? null;
                    @endphp
                    <flux:badge class="!text-xs" :style="'background-color: ' . ($taxonomy->color ?? 'transparent')">
                        {{ $taxonomy->name_es ?? 'N/A' }}
                    </flux:badge>
                    @endforeach
                </div>
                {{-- Sabores Secundarios --}}
                <div data-flux-offering-tastes>
                    @php
                    $secondary = $offering->getTastes([1,2]);
                    $secondaryRefs = array_column($secondary, 'ref');
                    $taxonomies_sec = OlfactoryTaxonomy::byRefs($secondaryRefs)->get()->keyBy('id');
                    @endphp
                    @foreach($secondary as $cata)
                    @php $taxonomy = $taxonomies_sec[$cata['ref']] ?? null; @endphp
                    <flux:badge class="!text-xs" :style="'background-color: ' . ($taxonomy->color ?? 'transparent') . '99'">
                        {{ $taxonomy->name_es ?? 'N/A' }}
                    </flux:badge>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Metadata: evaluaciones --}}
        <div data-flux-offering-meta-container>
            <div data-flux-offering-meta class="flex flex-col items-end gap-0">
                <span class="tz-offering-eval-count">{{ $offering->evaluation_count ?? 0 }} eval.</span>
                <span class="tz-offering-defective" data-flux-icon="alert-circle">
                    {{ $offering->defective_evaluation_count }} defectuosa
                </span>
            </div>
        </div>
    </div>
    {{-- Botones --}}
    <div class="tz-top-border-card" data-flux-offering-actions>
        <flux:button variant="outline" size="sm" icon="eye" href="">Ver</flux:button>
        <flux:button variant="outline" size="sm" icon="plus" href="{{route('evaluations.create', array_merge(request()->query(), ['offering' => $offering->id]))}}">Evaluar</flux:button>
    </div>
</div>