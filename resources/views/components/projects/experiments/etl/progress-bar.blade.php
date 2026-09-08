@props([
    'etlRun',
])

@php
    $progress = max(0, min(100, (int) ($etlRun->progress_percent ?? 0)));

    $barClass = match ($etlRun->status?->value) {
        'completed' => 'bg-success',
        'failed' => 'bg-danger',
        'cancelled' => 'bg-warning',
        default => 'bg-primary',
    };
@endphp

<div class="mb-3">
    <div class="d-flex justify-content-between small mb-1">
        <span class="fw-semibold">Overall Progress</span>
        <span>{{ $progress }}%</span>
    </div>

    <div class="progress" style="height:.8rem;">
        <div class="progress-bar {{ $barClass }} {{ $etlRun->isActive() ? 'progress-bar-striped progress-bar-animated' : '' }}"
             role="progressbar"
             style="width: {{ $progress }}%;"
             aria-valuenow="{{ $progress }}"
             aria-valuemin="0"
             aria-valuemax="100">
        </div>
    </div>
</div>
