@props([
    'project',
    'metrics' => [],
])

@php
    $totalStudies = (int) ($metrics['totalStudies'] ?? 0);
    $withFilesCount = (int) ($metrics['withFilesCount'] ?? 0);
    $withSamplesCount = (int) ($metrics['withSamplesCount'] ?? 0);
    $withActivitiesCount = (int) ($metrics['withActivitiesCount'] ?? 0);
    $withDatasetsCount = (int) ($metrics['withDatasetsCount'] ?? 0);
    $withDescriptionCount = (int) ($metrics['withDescriptionCount'] ?? 0);
    $readinessPercent = (int) ($metrics['readinessPercent'] ?? 0);

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
                    <i class="fas fa-flask me-1"></i>Studies Overview
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Study readiness based on associated files, samples, processes, descriptions, and datasets.
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
                    <div class="text-muted small">Total Studies</div>
                    <div class="fw-bold text-info">{{ number_format($totalStudies) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">With Files</div>
                    <div class="fw-bold text-primary">{{ number_format($withFilesCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">With Samples</div>
                    <div class="fw-bold text-success">{{ number_format($withSamplesCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">With Processes</div>
                    <div class="fw-bold text-secondary">{{ number_format($withActivitiesCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">With Datasets</div>
                    <div class="fw-bold text-success">{{ number_format($withDatasetsCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">With Description</div>
                    <div class="fw-bold text-warning">{{ number_format($withDescriptionCount) }}</div>
                </div>
            </div>
        </div>

        <div class="border rounded p-3 bg-light">
            <div class="fw-semibold small mb-1">
                <i class="fas fa-lightbulb text-warning me-1"></i>Recommended Study Action
            </div>

            @if($totalStudies === 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    This project does not have any studies yet. Create a study to group files, samples, and processes.
                </p>
                <a href="{{ route('projects.experiments.create', [$project]) }}"
                   class="btn btn-sm btn-outline-info">
                    <i class="fas fa-plus me-1"></i>Create Study
                </a>
            @elseif($withFilesCount < $totalStudies)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Some studies do not have files attached. Review study file coverage before publishing datasets.
                </p>
                <a href="{{ route('projects.experiments.index', [$project]) }}"
                   class="btn btn-sm btn-outline-info">
                    <i class="fas fa-list me-1"></i>Review Studies
                </a>
            @elseif($withSamplesCount < $totalStudies)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Some studies do not have samples linked. Add samples to improve traceability and metadata readiness.
                </p>
                <a href="{{ route('projects.experiments.index', [$project]) }}"
                   class="btn btn-sm btn-outline-info">
                    <i class="fas fa-list me-1"></i>Review Studies
                </a>
            @else
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Study coverage looks good from available counts. Review studies needing metadata or dataset links.
                </p>
                <a href="{{ route('projects.experiments.index', [$project]) }}"
                   class="btn btn-sm btn-outline-info">
                    <i class="fas fa-list me-1"></i>View Studies
                </a>
            @endif
        </div>
    </div>
</div>
