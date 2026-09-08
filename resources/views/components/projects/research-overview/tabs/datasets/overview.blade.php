@props([
    'project',
    'metrics' => [],
])

@php
    $totalDatasets = (int) ($metrics['totalDatasets'] ?? 0);
    $publishedCount = (int) ($metrics['publishedCount'] ?? 0);
    $draftCount = (int) ($metrics['draftCount'] ?? 0);
    $metadataPercent = (int) ($metrics['metadataPercent'] ?? 0);
    $coveragePercent = (int) ($metrics['coveragePercent'] ?? 0);
    $needsMetadataCount = collect($metrics['needsMetadata'] ?? [])->count();

    $readinessPercent = $totalDatasets > 0
        ? round(($metadataPercent + $coveragePercent + (int) ($metrics['publishedPercent'] ?? 0)) / 3)
        : 0;

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
                    <i class="fas fa-database me-1"></i>Datasets Overview
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Dataset readiness based on publication status, content coverage, and metadata completeness.
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
                    <div class="text-muted small">Published</div>
                    <div class="fw-bold text-success">{{ number_format($publishedCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Draft</div>
                    <div class="fw-bold text-warning">{{ number_format($draftCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Needs Metadata</div>
                    <div class="fw-bold text-danger">{{ number_format($needsMetadataCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Views</div>
                    <div class="fw-bold text-primary">{{ number_format((int) ($metrics['totalViews'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Downloads</div>
                    <div class="fw-bold text-primary">{{ number_format((int) ($metrics['totalDownloads'] ?? 0)) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Zip Size</div>
                    <div class="fw-bold text-secondary">{{ formatBytes((int) ($metrics['totalZipfileSize'] ?? 0)) }}</div>
                </div>
            </div>
        </div>

        <div class="border rounded p-3 bg-light">
            <div class="fw-semibold small mb-1">
                <i class="fas fa-lightbulb text-warning me-1"></i>Recommended Dataset Action
            </div>

            @if($totalDatasets === 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    This project does not have datasets yet. Create a dataset when files, studies, and metadata are ready.
                </p>
                <a href="{{ route('projects.datasets.create', [$project]) }}"
                   class="btn btn-sm btn-outline-success">
                    <i class="fas fa-plus me-1"></i>Create Dataset
                </a>
            @elseif($needsMetadataCount > 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Some datasets are missing metadata such as description, authors, license, tags, DOI, files, or study links.
                </p>
                <a href="{{ route('projects.datasets.index', [$project]) }}"
                   class="btn btn-sm btn-outline-success">
                    <i class="fas fa-list me-1"></i>Review Datasets
                </a>
            @elseif($draftCount > 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Draft datasets look ready for review. Consider publishing or completing publication preparation.
                </p>
                <a href="{{ route('projects.datasets.index', [$project]) }}"
                   class="btn btn-sm btn-outline-success">
                    <i class="fas fa-list me-1"></i>Review Drafts
                </a>
            @else
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Dataset readiness looks good. Review engagement and keep metadata current as the project evolves.
                </p>
                <a href="{{ route('projects.datasets.index', [$project]) }}"
                   class="btn btn-sm btn-outline-success">
                    <i class="fas fa-list me-1"></i>View Datasets
                </a>
            @endif
        </div>
    </div>
</div>
