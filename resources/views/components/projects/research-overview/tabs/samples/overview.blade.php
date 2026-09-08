@props([
    'project',
    'metrics' => [],
])

@php
    $totalSamples = (int) ($metrics['totalSamples'] ?? 0);
    $readinessPercent = (int) ($metrics['readinessPercent'] ?? 0);
    $needsReviewCount = collect($metrics['samplesNeedingReview'] ?? [])->count();

    $readinessColor = match (true) {
        $readinessPercent >= 80 => 'success',
        $readinessPercent >= 60 => 'info',
        $readinessPercent >= 40 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-cubes me-1"></i>Samples Overview
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Sample readiness based on measurements, processes, studies, files, datasets, tags, and descriptions.
                </p>
            </div>

            <span class="badge text-bg-{{ $readinessColor }}">
                {{ $readinessPercent }}% ready
            </span>
        </div>

        <div class="progress mb-3" style="height:.65rem;">
            <div class="progress-bar bg-{{ $readinessColor }}"
                 role="progressbar"
                 style="width: {{ $readinessPercent }}%;"
                 aria-valuenow="{{ $readinessPercent }}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Samples</div>
                    <div class="fw-bold text-success">{{ number_format($totalSamples) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Measurements</div>
                    <div class="fw-bold text-warning">{{ number_format((int) ($metrics['totalMeasurements'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Processes</div>
                    <div class="fw-bold text-secondary">{{ number_format((int) ($metrics['totalProcesses'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Files</div>
                    <div class="fw-bold text-primary">{{ number_format((int) ($metrics['totalFiles'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Studies</div>
                    <div class="fw-bold text-info">{{ number_format((int) ($metrics['totalStudies'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Needs Review</div>
                    <div class="fw-bold text-warning">{{ number_format($needsReviewCount) }}</div>
                </div>
            </div>
        </div>

        <div class="border rounded p-3 bg-light">
            <div class="fw-semibold small mb-1">
                <i class="fas fa-lightbulb text-warning me-1"></i>Recommended Sample Action
            </div>

            @if($totalSamples === 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    This project does not have samples yet. Samples usually come from studies or imported experiment data.
                </p>
                <a href="{{ route('projects.experiments.index', [$project]) }}"
                   class="btn btn-sm btn-outline-info">
                    <i class="fas fa-flask me-1"></i>Review Studies
                </a>
            @elseif($needsReviewCount > 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Some samples are missing studies, processes, measurements, files, descriptions, or tags.
                </p>
                <a href="{{ route('projects.data-dictionary.entities', [$project]) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-list me-1"></i>Review Sample Attributes
                </a>
            @else
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Sample readiness looks good from available counts. Review sample attributes as the project evolves.
                </p>
                <a href="{{ route('projects.entities.index', [$project, 'category' => 'experimental']) }}"
                   class="btn btn-sm btn-outline-success">
                    <i class="fas fa-cubes me-1"></i>View Samples
                </a>
            @endif
        </div>
    </div>
</div>
