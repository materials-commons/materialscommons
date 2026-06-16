@props([
    'project',
    'metrics' => [],
])

@php
    $licenseCounts = collect($metrics['licenseCounts'] ?? []);
    $total = max(1, (int) ($metrics['totalDatasets'] ?? 0));
    $withLicenseCount = (int) ($metrics['withLicenseCount'] ?? 0);
    $licensePercent = (int) round(($withLicenseCount / $total) * 100);

    $licenseColor = match (true) {
        $licensePercent >= 90 => 'success',
        $licensePercent >= 60 => 'info',
        $licensePercent >= 40 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-balance-scale me-1"></i>License Coverage
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    License distribution for datasets in this project.
                </p>
            </div>

            <span class="badge text-bg-{{ $licenseColor }}">
                {{ $licensePercent }}% licensed
            </span>
        </div>

        @if((int) ($metrics['totalDatasets'] ?? 0) === 0)
            <div class="text-center text-muted py-4">
                <i class="fas fa-balance-scale fa-2x mb-2"></i>
                <div class="fw-semibold">No licenses yet</div>
                <div style="font-size:.85rem;">Create datasets to track license coverage.</div>
            </div>
        @else
            @foreach($licenseCounts->take(8) as $row)
                @php
                    $count = (int) ($row->datasets_count ?? 0);
                    $percent = round(($count / $total) * 100);
                    $isMissing = ($row->license ?? '') === 'Missing License';
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between gap-2 mb-1">
                        <div class="small text-muted text-truncate">{{ $row->license }}</div>
                        <div class="small fw-semibold">
                            {{ number_format($count) }}
                            <span class="text-muted fw-normal">({{ $percent }}%)</span>
                        </div>
                    </div>

                    <div class="progress" style="height:.45rem;">
                        <div class="progress-bar bg-{{ $isMissing ? 'danger' : 'success' }}"
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
