<div class="mb-2" data-flux-card>
    <div class="flex max-lg:flex-col justify-between">
        {{-- Lado izquierdo: contenido --}}
        <div data-flux-card-content>
            {{-- Header: badge de estado + título --}}
            <div class="flex max-md:flex-col gap-2 items-center">
                @if($evaluation->status === 'closed')
                <flux:badge icon="check" class="tz-badge-provisional">Cerrada</flux:badge>
                @else
                <flux:badge icon="clock" class="tz-badge-verified">Abierta</flux:badge>
                @endif
                <flux:heading level="3">{{ ucfirst($evaluation->offering->coffee->name) }}</flux:heading>
            </div>

            {{-- Taza defectuosa --}}
            @if($evaluation->affective['is_defective'] ?? false)
            <span class="tz-offering-defective" data-flux-icon="alert-circle">
                Taza defectuosa
            </span>
            @endif

            {{-- Cafetería --}}
            <div data-flux-offering-shop>
                <span class="tz-offering-label text-sm max-md:text-xs">Cafetería:</span>
                <flux:link href="" class="!text-xs max-md:!text-2xs">
                    {{ $evaluation->offering->location->name }}
                </flux:link>
            </div>

            {{-- Tostadora --}}
            <div data-flux-offering-shop>
                <span class="tz-offering-label text-sm max-md:text-xs">Tostadora:</span>
                <flux:link href="" class="!text-sm max-md:!text-xs">
                    {{ $evaluation->offering->coffee->roastery ?? 'N/A' }}
                </flux:link>
            </div>
        </div>

        {{-- Lado derecho: puntaje --}}
        <div class="tz-left-border-card" data-flux-card-score>
            @php
            $score = $evaluation->affective['cupping_score'] ?? 0;

            if ($score >= 90) {
            $badge_color = 'tz-rating-exceptional';
            } elseif ($score >= 85) {
            $badge_color = 'tz-rating-excellent';
            } elseif ($score >= 80) {
            $badge_color = 'tz-rating-good';
            } else {
            $badge_color = 'tz-rating-commercial';
            }
            @endphp
            <flux:badge class="{{ $badge_color }}">
                <div data-flux-card-value>{{ $score }}</div>
                <div data-flux-card-scale>/100</div>
            </flux:badge>
            <div data-flux-card-scale>Puntaje</div>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex" data-flux-card-actions>
        @if($evaluation->status === 'open')
        <form method="POST" action="{{ route('evaluations.update', $evaluation) }}" onsubmit="return confirm('¿Cerrar esta evaluación?')">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="closed">
            <flux:button type="submit" variant="outline" size="sm" data-tz-action="close">Cerrar Evaluación</flux:button>
        </form>

        @if($evaluation->evaluator_role === 'specialist')
        <flux:button variant="outline" size="sm" data-tz-action="edit" href="{{ route('evaluations.edit', $evaluation) }}">Editar</flux:button>
        @endif
        @endif
        <form method="POST" action="{{ route('evaluations.destroy', $evaluation) }}" onsubmit="return confirm('¿Eliminar esta evaluación?')">
            @csrf
            @method('DELETE')
            <flux:button type="submit" variant="outline" size="sm" data-tz-action="delete">Eliminar</flux:button>
        </form>
    </div>
</div>