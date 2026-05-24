@props([
    'label',
    'value' => 0,
    'hint' => null,
    'color' => 'primary',
    'icon' => 'fas fa-chart-bar',
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div class="min-width-0">
                <div class="text-muted text-uppercase mb-1" style="font-size:.68rem; letter-spacing:.05em;">
                    {{ $label }}
                </div>

                <div class="fw-semibold text-{{ $color }}" style="font-size:1.5rem; line-height:1.1;">
                    {{ number_format($value) }}
                </div>

                @if(filled($hint))
                    <div class="text-muted text-truncate mt-1" style="font-size:.74rem;">
                        {{ $hint }}
                    </div>
                @endif
            </div>

            <span class="badge rounded-pill text-bg-{{ $color }} bg-opacity-75 flex-shrink-0">
                <i class="{{ $icon }}"></i>
            </span>
        </div>
    </div>
</div>
