@props([
    'label',
    'value' => 0,
    'hint' => null,
    'color' => 'primary',
    'icon' => 'fas fa-tags',
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2">
            <div>
                <div class="text-muted text-uppercase" style="font-size:.7rem; letter-spacing:.04em;">
                    {{ $label }}
                </div>
                <div class="fw-semibold text-{{ $color }}" style="font-size:1.45rem; line-height:1.1;">
                    {{ number_format($value) }}
                </div>

                @if(filled($hint))
                    <div class="text-muted" style="font-size:.75rem;">
                        {{ $hint }}
                    </div>
                @endif
            </div>

            <span class="badge rounded-pill text-bg-{{ $color }}">
                <i class="{{ $icon }}"></i>
            </span>
        </div>
    </div>
</div>
