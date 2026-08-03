@props([
    'project',
    'metrics' => [],
])

@php
    $categoryCounts = collect($metrics['categoryCounts'] ?? []);
    $total = max(1, (int) ($metrics['totalSamples'] ?? 0));
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-pie me-1"></i>Sample Category Distribution
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Distribution of sample categories in this project.
                </p>
            </div>

            <span class="badge text-bg-success">
                {{ number_format($categoryCounts->count()) }} categories
            </span>
        </div>

        @if((int) ($metrics['totalSamples'] ?? 0) === 0)
            <div class="text-center text-muted py-4">
                <i class="fas fa-chart-pie fa-2x mb-2"></i>
                <div class="fw-semibold">No sample categories yet</div>
                <div style="font-size:.85rem;">Samples will appear after studies or experiment data are loaded.</div>
            </div>
        @else
            @foreach($categoryCounts->take(8) as $row)
                @php
                    $count = (int) ($row->samples_count ?? 0);
                    $percent = round(($count / $total) * 100);
                    $isExperimental = ($row->category ?? '') === 'experimental';
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between gap-2 mb-1">
                        <div class="small text-muted text-break">{{ $row->category }}</div>
                        <div class="small fw-semibold">
                            {{ number_format($count) }}
                            <span class="text-muted fw-normal">({{ $percent }}%)</span>
                        </div>
                    </div>

                    <div class="progress" style="height:.45rem;">
                        <div class="progress-bar bg-{{ $isExperimental ? 'success' : 'warning' }}"
                             role="progressbar"
                             style="width: {{ $percent }}%;"
                             aria-valuenow="{{ $percent }}"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
