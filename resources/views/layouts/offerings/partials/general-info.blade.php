@props(['offering'])

<div class="tz-form-main flex flex-col gap-4">
    <div class="flex flex-col gap-3">
        <flux:subheading class="tz-subtitle">Origen</flux:subheading>
        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Origen</span>
                <span>{{ $offering->coffee->extrinsics['origin']['region'] ?? 'N/A' }}, {{ $offering->coffee->extrinsics['origin']['country'] ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Productor</span>
                <span>{{ $offering->coffee->extrinsics['producer'] ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Variedad</span>
                <span>{{ $offering->coffee->extrinsics['variety'] ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Proceso</span>
                <span>{{ $offering->coffee->extrinsics['process'] ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Lote</span>
                <span>{{ $offering->coffee->extrinsics['lot'] ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Trazabilidad</span>
                <span>{{ $offering->coffee->extrinsics['traceability'] ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Certificación</span>
                @if(!empty($offering->coffee->extrinsics['certification']))
                <flux:badge class="tz-badge-verified">{{ $offering->coffee->extrinsics['certification'] }}</flux:badge>
                @else
                <span>N/A</span>
                @endif
            </div>
        </div>
    </div>

    <div class="tz-top-border-card flex flex-col gap-3 pt-4">
        <flux:subheading class="tz-subtitle">Estado</flux:subheading>
        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Verificación</span>
                @if($offering->verification_status === 'verified')
                <span class="flex items-center gap-1" style="color: var(--color-text-filter-label)">
                    <flux:icon.check-circle class="size-4" /> Verificado
                </span>
                @else
                <span class="flex items-center gap-1" style="color: var(--color-warmgray-500)">
                    <flux:icon.clock class="size-4" /> Provisional
                </span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Concordancia</span>
                <flux:badge>{{ ucfirst($offering->concordance ?? 'N/A') }}</flux:badge>
            </div>
        </div>
    </div>

    <div class="tz-top-border-card flex flex-col gap-3 pt-4">
        <flux:subheading class="tz-subtitle">Evaluaciones</flux:subheading>
        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Total</span>
                <span>{{ $offering->evaluation_count ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="tz-offering-label">Defectuosas</span>
                <span class="tz-offering-defective" data-flux-icon="alert-circle">{{ $offering->defective_evaluation_count ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>
