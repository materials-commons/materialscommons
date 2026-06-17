@props([
    'project',
    'metrics' => [],
])

@php
    $typeCounts = collect($metrics['typeCounts'] ?? []);
    $categoryCounts = collect($metrics['categoryCounts'] ?? []);
    $total = max(1, (int) ($metrics['totalProcesses'] ?? 0));
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-pie me-1"></i>Process Type Distribution
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Distribution of process types and categories.
                </p>
            </div>

            <span class="badge text-bg-info">
                {{ number_format($typeCounts->count()) }} types
            </span>
        </div>

        @if((int) ($metrics['totalProcesses'] ?? 0) === 0)
            <div class="text-center text-muted py-4">
                <i class="fas fa-chart-pie fa-2x mb-2"></i>
                <div class="fw-semibold">No process types yet</div>
                <div style="font-size:.85rem;">Processes will appear after studies or experiment data are loaded.</div>
            </div>
        @else
            <div class="mb-3">
                <div class="text-muted small fw-semibold mb-2">Top Types</div>

                @foreach($typeCounts->take(6) as $row)
                    @php
                        $count = (int) ($row->processes_count ?? 0);
                        $percent = round(($count / $total) * 100);
                        $isMissing = ($row->type ?? '') === 'Unspecified Type';
                    @endphp

                    <div class="mb-2">
                        <div class="d-flex justify-content-between gap-2 mb-1">
                            <div class="small text-muted text-break">{{ $row->type }}</div>
                            <div class="small fw-semibold">
                                {{ number_format($count) }}
                                <span class="text-muted fw-normal">({{ $percent }}%)</span>
                            </div>
                        </div>

                        <div class="progress" style="height:.45rem;">
                            <div class="progress-bar bg-{{ $isMissing ? 'warning' : 'info' }}"
                                 role="progressbar"
                                 style="width: {{ $percent }}%;"
                                 aria-valuenow="{{ $percent }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border rounded p-2 bg-light">
                <div class="text-muted small fw-semibold mb-1">Categories</div>

                @foreach($categoryCounts->take(5) as $row)
                    <div class="d-flex justify-content-between gap-2" style="font-size:.82rem;">
                        <span class="text-muted text-break">{{ $row->category }}</span>
                        <span class="fw-semibold">{{ number_format((int) ($row->processes_count ?? 0)) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
