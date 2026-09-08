@props([
    'steps' => collect(),
])

<div>
    <div class="text-muted text-uppercase fw-semibold mb-2" style="font-size:.72rem; letter-spacing:.04em;">
        Steps
    </div>

    <div class="d-flex flex-column">
        @forelse($steps as $step)
            @php
                $color = $step->status?->colorClass() ?? 'secondary';
                $icon = $step->status?->iconClass() ?? 'fas fa-clock';
            @endphp

            <div class="d-flex gap-3 py-2 border-bottom">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0 bg-{{ $color }} text-white"
                     style="width:2rem;height:2rem;">
                    <i class="{{ $icon }}" style="font-size:.78rem;"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $step->label }}</div>
                    @if(!blank($step->message))
                        <div class="text-muted small">{{ $step->message }}</div>
                    @endif
                    <div class="text-muted" style="font-size:.7rem;">
                        {{ $step->status?->label() ?? 'Waiting' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted small">No steps recorded yet.</div>
        @endforelse
    </div>
</div>
