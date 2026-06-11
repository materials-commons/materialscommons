@props([
    'project',
    'readme' => null,
])

@php
    $healthLabel = match ($project->health) {
        'critical' => 'Critical',
        'warning' => 'Warning',
        null => 'Unknown',
        default => 'Healthy',
    };

    $healthColor = match ($project->health) {
        'critical' => 'danger',
        'warning' => 'warning',
        null => 'secondary',
        default => 'success',
    };

    $hasReadme = filled($readme);
    $hasDescription = filled($project->description ?? null) || filled($project->summary ?? null);
    $hasDraftDatasets = (int) ($project->unpublished_datasets_count ?? 0) > 0;

    $attentionCount = collect([
        $project->health !== 'healthy',
        ! $hasReadme,
        ! $hasDescription,
        $hasDraftDatasets,
    ])->filter()->count();
@endphp

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <h6 class="card-title text-muted mb-0">
                <i class="fas fa-exclamation-circle me-1"></i>Needs Attention
            </h6>

            <span class="badge text-bg-{{ $attentionCount > 0 ? 'warning' : 'success' }}">
                {{ $attentionCount }} item{{ $attentionCount === 1 ? '' : 's' }}
            </span>
        </div>

        <div class="row g-2">
            <div class="col-12 col-lg-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold small mb-1">
                        <i class="fas fa-heartbeat text-{{ $healthColor }} me-1"></i>Project Health
                    </div>
                    <p class="text-muted mb-2" style="font-size:.82rem;">
                        Current health state is <span class="fw-semibold">{{ $healthLabel }}</span>.
                        Review health reports for critical issues, warnings, and suggested fixes.
                    </p>
                    <a href="{{ route('projects.health-reports.index', [$project]) }}"
                       class="btn btn-sm btn-outline-{{ $healthColor }}">
                        Review Health Reports
                    </a>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold small mb-1">
                        <i class="fas fa-clipboard-check text-warning me-1"></i>Metadata Readiness
                    </div>
                    <p class="text-muted mb-2" style="font-size:.82rem;">
                        @if($hasReadme && $hasDescription)
                            Project README and description are present. Review sample and process attributes next.
                        @else
                            Add a README or project description so collaborators understand this project.
                        @endif
                    </p>
                    <button type="button"
                            class="btn btn-sm btn-outline-warning js-project-dashboard-show-tab"
                            data-tab-target="#tab-project-dashboard-metadata">
                        Review Metadata
                    </button>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold small mb-1">
                        <i class="fas fa-database text-success me-1"></i>Publishing Readiness
                    </div>
                    <p class="text-muted mb-2" style="font-size:.82rem;">
                        @if($hasDraftDatasets)
                            {{ number_format($project->unpublished_datasets_count) }} draft dataset(s) may need
                            metadata, authors, license information, or publication review.
                        @else
                            No draft datasets are currently flagged from project counts.
                        @endif
                    </p>
                    <a href="{{ route('projects.datasets.index', [$project]) }}"
                       class="btn btn-sm btn-outline-success">
                        Review Datasets
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
