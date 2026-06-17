@props([
    'project',
    'metrics' => [],
])

@php
    $total = max(1, (int) ($metrics['totalSamples'] ?? 0));
    $coverageItems = collect($metrics['coverageItems'] ?? []);
    $coveragePercent = (int) ($metrics['coveragePercent'] ?? 0);

    $coverageColor = match (true) {
        $coveragePercent >= 80 => 'success',
        $coveragePercent >= 60 => 'info',
        $coveragePercent >= 40 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-project-diagram me-1"></i>Sample Coverage
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Sample linkage across studies, processes, files, datasets, and measurements.
                </p>
            </div>

            <span class="badge text-bg-{{ $coverageColor }}">
                {{ $coveragePercent }}% coverage
            </span>
        </div>

        @if((int) ($metrics['totalSamples'] ?? 0) === 0)
            <div class="text-center text-muted py-4">
                <i class="fas fa-cubes fa-2x mb-2"></i>
                <div class="fw-semibold">No samples yet</div>
                <div style="font-size:.85rem;">Samples will appear after studies or experiment data are loaded.</div>
            </div>
        @else
            @foreach($coverageItems as $item)
                @php
                    $percent = round(((int) $item['value'] / $total) * 100);
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between gap-2 mb-1">
                        <div class="small text-muted">{{ $item['label'] }}</div>
                        <div class="small fw-semibold">
                            {{ number_format((int) $item['value']) }}
                            <span class="text-muted fw-normal">({{ $percent }}%)</span>
                        </div>
                    </div>

                    <div class="progress" style="height:.45rem;">
                        <div class="progress-bar bg-{{ $item['color'] }}"
                             role="progressbar"
                             style="width: {{ $percent }}%;"
                             aria-valuenow="{{ $percent }}"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>

                    <div class="text-muted mt-1" style="font-size:.72rem;">{{ $item['hint'] }}</div>
                </div>
            @endforeach
        @endif
    </div>
</div>
