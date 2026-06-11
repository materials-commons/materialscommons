@props([
    'project',
])

@php
    $membersCount = collect($project->team?->members ?? collect())->count();

    $datasetsCount = (int) ($project->published_datasets_count ?? 0)
        + (int) ($project->unpublished_datasets_count ?? 0);

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
@endphp

<ul class="nav nav-pills mb-3" id="project-dashboard-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active"
                id="project-dashboard-overview-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-overview"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-overview"
                aria-selected="true">
            <i class="fas fa-home me-1"></i>Overview
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-files-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-files"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-files"
                aria-selected="false">
            <i class="fas fa-folder-open me-1"></i>Files
            <span class="badge text-bg-primary ms-1">{{ number_format($project->file_count) }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-studies-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-studies"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-studies"
                aria-selected="false">
            <i class="fas fa-flask me-1"></i>Studies
            <span class="badge text-bg-info ms-1">{{ number_format($project->experiments_count) }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-datasets-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-datasets"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-datasets"
                aria-selected="false">
            <i class="fas fa-database me-1"></i>Datasets
            <span class="badge text-bg-success ms-1">{{ number_format($datasetsCount) }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-samples-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-samples"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-samples"
                aria-selected="false">
            <i class="fas fa-cubes me-1"></i>Samples
            <span class="badge text-bg-secondary ms-1">{{ number_format($project->entities_count) }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-processes-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-processes"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-processes"
                aria-selected="false">
            <i class="fas fa-cogs me-1"></i>Processes
            <span class="badge text-bg-secondary ms-1">{{ number_format($project->activityAttributesCount ?? 0) }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-metadata-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-metadata"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-metadata"
                aria-selected="false">
            <i class="fas fa-clipboard-check me-1"></i>Metadata
            <span class="badge text-bg-warning ms-1">
                {{ number_format(($project->entityAttributesCount ?? 0) + ($project->activityAttributesCount ?? 0)) }}
            </span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-collaborators-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-collaborators"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-collaborators"
                aria-selected="false">
            <i class="fas fa-users me-1"></i>Collaborators
            <span class="badge text-bg-primary ms-1">{{ number_format($membersCount) }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-health-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-health"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-health"
                aria-selected="false">
            <i class="fas fa-heartbeat me-1"></i>Health
            <span class="badge text-bg-{{ $healthColor }} ms-1">{{ $healthLabel }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                id="project-dashboard-activity-tab"
                data-bs-toggle="pill"
                data-bs-target="#tab-project-dashboard-activity"
                type="button"
                role="tab"
                aria-controls="tab-project-dashboard-activity"
                aria-selected="false">
            <i class="fas fa-history me-1"></i>Activity
        </button>
    </li>
</ul>
