@props([
    'label',
    'value',
    'total' => null,
    'color' => 'primary',
    'hint' => null,
])

@php
    $numericValue = is_numeric($value) ? (float) $value : 0;
    $numericTotal = is_numeric($total) ? (float) $total : null;

    $percent = $numericTotal !== null && $numericTotal > 0
        ? min(100, max(0, round(($numericValue / $numericTotal) * 100)))
        : null;
@endphp

<div class="mb-3">
    <div class="d-flex justify-content-between gap-2 mb-1">
        <div class="small text-muted">{{ $label }}</div>
        <div class="small fw-semibold">
            {{ is_numeric($value) ? number_format($value) : $value }}
            @if($percent !== null)
                <span class="text-muted fw-normal">({{ $percent }}%)</span>
            @endif
        </div>
    </div>

    @if($percent !== null)
        <div class="progress" style="height: .45rem;">
            <div class="progress-bar bg-{{ $color }}"
                 role="progressbar"
                 style="width: {{ $percent }}%;"
                 aria-valuenow="{{ $percent }}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>
    @endif

    @if(filled($hint))
        <div class="text-muted mt-1" style="font-size:.72rem;">{{ $hint }}</div>
    @endif
</div>
