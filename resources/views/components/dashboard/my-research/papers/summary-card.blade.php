@props([
    'label',
    'value' => 0,
    'hint' => null,
    'color' => 'secondary',
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2">
            <div>
                <div class="text-muted text-uppercase" style="font-size:.72rem; letter-spacing:.04em;">
                    {{ $label }}
                </div>

                <div class="fw-bold text-{{ $color }}" style="font-size:1.45rem; line-height:1.1;">
                    {{ number_format($value) }}
                </div>

                @if(filled($hint))
                    <div class="text-muted" style="font-size:.78rem;">
                        {{ $hint }}
                    </div>
                @endif
            </div>

            <div class="rounded-circle bg-{{ $color }} bg-opacity-10 text-{{ $color }} d-flex align-items-center justify-content-center"
                 style="width:34px; height:34px;">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>
</div>
