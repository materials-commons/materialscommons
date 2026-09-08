@props([
    'project',
    'metrics' => [],
])

@php
    $totalStudies = (int) ($metrics['totalStudies'] ?? 0);
    $denominator = max(1, $totalStudies);
    $coverageItems = collect($metrics['coverageItems'] ?? []);
    $overallCoverage = (int) ($metrics['overallCoverage'] ?? 0);

    $overallColor = match (true) {
        $overallCoverage >= 80 => 'success',
        $overallCoverage >= 60 => 'info',
        $overallCoverage >= 40 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-project-diagram me-1"></i>Study Coverage
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Coverage across files, samples, processes, datasets, and descriptions.
                </p>
            </div>

            <span class="badge text-bg-{{ $overallColor }}">
                {{ $overallCoverage }}% coverage
            </span>
        </div>

        @if($totalStudies === 0)
            <div class="text-center text-muted py-4">
                <i class="fas fa-flask fa-2x mb-2"></i>
                <div class="fw-semibold">No studies yet</div>
                <div style="font-size:.85rem;">Create a study to see coverage analytics.</div>
            </div>
        @else
            @foreach($coverageItems as $item)
                @php
                    $percent = round(((int) $item['value'] / $denominator) * 100);
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
