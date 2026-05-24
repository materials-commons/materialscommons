@php
    $projectDashboardTabKey = 'mc_project_dashboard_tab_' . $project->id;

    $membersCount = collect($project->team?->members ?? collect())->count();
    $adminsCount = collect($project->team?->admins ?? collect())->count();

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

{{-- ══ Project header ═══════════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3 background-white">
        <div class="d-flex gap-4 align-items-start">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-light border"
                 style="width:80px; height:80px;">
                <i class="fas fa-folder-open fa-2x text-muted"></i>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                    <div>
                        <h4 class="mb-1">{{ $project->name }}</h4>
                        <p class="text-muted mb-2" style="font-size:.9rem;">
                            A private project overview of files, studies, datasets, samples, processes,
                            collaborators, health, and metadata readiness.
                        </p>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-folder-open me-1"></i>Browse Files
                        </a>

                        <a href="{{ route('projects.upload-files', [$project]) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-upload me-1"></i>Upload
                        </a>

                        <a href="{{ route('projects.experiments.create', [$project]) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-flask me-1"></i>New Study
                        </a>

                        <a href="{{ route('projects.datasets.create', [$project]) }}"
                           class="btn btn-sm btn-outline-success">
                            <i class="fas fa-database me-1"></i>New Dataset
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3">
                    <span class="text-muted">
                        <i class="fas fa-user me-1" style="font-size:.8rem;"></i>
                        Owner: {{ $project->owner->name }}
                    </span>

                    <span class="text-muted">
                        <i class="fas fa-users me-1" style="font-size:.8rem;"></i>
                        {{ $membersCount }} member(s), {{ $adminsCount }} admin(s)
                    </span>

                    <span class="text-muted">
                        <i class="far fa-clock me-1" style="font-size:.8rem;"></i>
                        Updated {{ $project->updated_at->diffForHumans() }}
                    </span>

                    <span class="text-muted">
                        <i class="fas fa-hdd me-1" style="font-size:.8rem;"></i>
                        {{ formatBytes($project->size) }}
                    </span>

                    <span class="badge text-bg-{{ $healthColor }}">
                        <i class="fas fa-heartbeat me-1"></i>{{ $healthLabel }}
                    </span>

                    @if(auth()->user()->isActiveProject($project))
                        <span class="badge text-bg-warning">
                            <i class="fas fa-star me-1"></i>Active Project
                        </span>
                    @endif
                </div>

                @if(filled($project->description ?? null))
                    <p class="text-muted mt-2 mb-0" style="font-size:.85rem;">
                        {{ $project->description }}
                    </p>
                @elseif(filled($project->summary ?? null))
                    <p class="text-muted mt-2 mb-0" style="font-size:.85rem;">
                        {{ $project->summary }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══ KPI strip placeholder ════════════════════════════════════════════════ --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Files</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($project->file_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">
                {{ number_format($project->directory_count) }} folders
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Studies</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($project->experiments_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">experiments</div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Samples</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($project->entities_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">tracked entities</div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Datasets</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($datasetsCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">
                {{ number_format($project->published_datasets_count) }} published
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Storage</div>
            <div class="fw-bold fs-5 text-secondary">{{ formatBytes($project->size) }}</div>
            <div class="text-muted" style="font-size:.65rem;">total used</div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Members</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($membersCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">
                {{ number_format($adminsCount) }} admin(s)
            </div>
        </div>
    </div>
</div>

{{-- ══ Needs attention placeholder ══════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <h6 class="card-title text-muted mb-0">
                <i class="fas fa-exclamation-circle me-1"></i>Needs Attention
            </h6>

            <span class="badge text-bg-light text-secondary">Placeholder</span>
        </div>

        <div class="row g-2">
            <div class="col-12 col-lg-4">
                <div class="border rounded p-3 h-100">
                    <div class="fw-semibold small mb-1">
                        <i class="fas fa-heartbeat text-{{ $healthColor }} me-1"></i>Project Health
                    </div>
                    <p class="text-muted mb-2" style="font-size:.82rem;">
                        Placeholder for critical and warning health report items.
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
                        Placeholder for missing README, descriptions, dataset metadata, or attribute coverage.
                    </p>
                    <button type="button" class="btn btn-sm btn-outline-warning" disabled>
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
                        Placeholder for draft datasets, missing licenses, missing authors, and unpublished work.
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

{{-- ══ Analytics placeholder ════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <h6 class="card-title text-muted mb-0">
                <i class="fas fa-chart-bar me-1"></i>Project Analytics
            </h6>

            <button class="btn btn-sm btn-outline-secondary"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#project-dashboard-analytics"
                    aria-expanded="false"
                    aria-controls="project-dashboard-analytics">
                Toggle Analytics
            </button>
        </div>

        <div class="collapse" id="project-dashboard-analytics">
            <div class="row g-3">
                <div class="col-12 col-xl-4">
                    <div class="border rounded p-3 h-100 text-center">
                        <i class="fas fa-chart-pie text-muted mb-2" style="font-size:2rem;"></i>
                        <h6 class="text-muted">Project Composition</h6>
                        <p class="text-muted mb-0" style="font-size:.82rem;">
                            Placeholder for files, folders, studies, samples, processes, and datasets chart.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="border rounded p-3 h-100 text-center">
                        <i class="fas fa-file-alt text-muted mb-2" style="font-size:2rem;"></i>
                        <h6 class="text-muted">File Types</h6>
                        <p class="text-muted mb-0" style="font-size:.82rem;">
                            Placeholder for file extension distribution.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="border rounded p-3 h-100 text-center">
                        <i class="fas fa-cogs text-muted mb-2" style="font-size:2rem;"></i>
                        <h6 class="text-muted">Process Types</h6>
                        <p class="text-muted mb-0" style="font-size:.82rem;">
                            Placeholder for process/activity distribution.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ Content tabs ═════════════════════════════════════════════════════════ --}}
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
            <span class="badge text-bg-secondary ms-1">—</span>
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
            <span class="badge text-bg-warning ms-1">—</span>
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

<div class="tab-content" id="project-dashboard-tabs-content">
    {{-- ── Overview ─────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade show active"
         id="tab-project-dashboard-overview"
         role="tabpanel"
         aria-labelledby="project-dashboard-overview-tab">
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3 background-white">
                        <h6 class="card-title text-muted">
                            <i class="fas fa-info-circle me-1"></i>Project Summary
                        </h6>
                        <p class="text-muted mb-0">
                            Placeholder for a concise project summary, key contents, recent changes,
                            and publishing readiness.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3 background-white">
                        <h6 class="card-title text-muted">
                            <i class="fas fa-lightbulb me-1"></i>Recommended Actions
                        </h6>
                        <p class="text-muted mb-0">
                            Placeholder for recommended next steps such as uploading files, creating studies,
                            improving metadata, reviewing health reports, or publishing datasets.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <x-display-markdown-file :file="$readme"></x-display-markdown-file>
        </div>
    </div>

    {{-- ── Files ────────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-files"
         role="tabpanel"
         aria-labelledby="project-dashboard-files-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-folder-open me-1"></i>Files
                </h6>
                <p class="text-muted">
                    Placeholder for file summary, recent uploads, largest files, file type distribution,
                    and files not yet connected to studies or datasets.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-folder-open me-1"></i>Browse Files
                    </a>

                    <a href="{{ route('projects.upload-files', [$project]) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-upload me-1"></i>Upload Files
                    </a>

                    <a href="{{ route('projects.globus.uploads.index', [$project]) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-exchange-alt me-1"></i>Globus
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Studies ──────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-studies"
         role="tabpanel"
         aria-labelledby="project-dashboard-studies-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-flask me-1"></i>Studies
                </h6>
                <p class="text-muted">
                    Placeholder for study summary, recent studies, studies missing samples/files,
                    and study-to-dataset readiness.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('projects.experiments.index', [$project]) }}"
                       class="btn btn-sm btn-outline-info">
                        <i class="fas fa-list me-1"></i>View Studies
                    </a>

                    <a href="{{ route('projects.experiments.create', [$project]) }}"
                       class="btn btn-sm btn-outline-info">
                        <i class="fas fa-plus me-1"></i>New Study
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Datasets ─────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-datasets"
         role="tabpanel"
         aria-labelledby="project-dashboard-datasets-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-database me-1"></i>Datasets
                </h6>
                <p class="text-muted">
                    Placeholder for published and unpublished datasets, missing metadata,
                    licenses, DOIs, authors, and publishing readiness.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('projects.datasets.index', [$project]) }}"
                       class="btn btn-sm btn-outline-success">
                        <i class="fas fa-list me-1"></i>View Datasets
                    </a>

                    <a href="{{ route('projects.datasets.create', [$project]) }}"
                       class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus me-1"></i>New Dataset
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Samples ──────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-samples"
         role="tabpanel"
         aria-labelledby="project-dashboard-samples-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-cubes me-1"></i>Samples
                </h6>
                <p class="text-muted">
                    Placeholder for sample/entity summary, sample types, sample attributes,
                    missing values, and recently created samples.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('projects.entities.index', [$project, 'category' => 'experimental']) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-cubes me-1"></i>View Samples
                    </a>

                    <a href="{{ route('projects.data-dictionary.entities', [$project]) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-list me-1"></i>Sample Attributes
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Processes ────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-processes"
         role="tabpanel"
         aria-labelledby="project-dashboard-processes-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-cogs me-1"></i>Processes
                </h6>
                <p class="text-muted">
                    Placeholder for process/activity summary, process types, process attributes,
                    missing values, and recently created processes.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('projects.data-dictionary.activities', [$project]) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-list me-1"></i>Process Attributes
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Metadata ─────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-metadata"
         role="tabpanel"
         aria-labelledby="project-dashboard-metadata-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-clipboard-check me-1"></i>Metadata Readiness
                </h6>
                <p class="text-muted mb-3">
                    Placeholder for project README, description, dataset metadata, sample attributes,
                    process attributes, and publishing readiness checks.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Suggested Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="5" class="text-muted text-center py-4">
                                Metadata readiness placeholder
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Collaborators ────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-collaborators"
         role="tabpanel"
         aria-labelledby="project-dashboard-collaborators-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-users me-1"></i>Collaborators
                </h6>
                <p class="text-muted">
                    Placeholder for owner, admins, members, recent contributors,
                    inactive collaborators, and dataset authors.
                </p>

                <a href="{{ route('projects.users.index', [$project]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-users me-1"></i>Manage Members
                </a>
            </div>
        </div>
    </div>

    {{-- ── Health ───────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-health"
         role="tabpanel"
         aria-labelledby="project-dashboard-health-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-heartbeat me-1"></i>Health
                </h6>
                <p class="text-muted">
                    Placeholder for project health status, critical issues, warnings,
                    last health check, and recommended fixes.
                </p>

                <a href="{{ route('projects.health-reports.index', [$project]) }}"
                   class="btn btn-sm btn-outline-{{ $healthColor }}">
                    <i class="fas fa-heartbeat me-1"></i>Open Health Reports
                </a>
            </div>
        </div>
    </div>

    {{-- ── Activity ─────────────────────────────────────────────────────── --}}
    <div class="tab-pane fade"
         id="tab-project-dashboard-activity"
         role="tabpanel"
         aria-labelledby="project-dashboard-activity-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-history me-1"></i>Recent Activity
                </h6>
                <p class="text-muted mb-0">
                    Placeholder for recent uploads, study changes, dataset changes,
                    metadata updates, member activity, and project events.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const TAB_KEY = @json($projectDashboardTabKey);

            document.querySelectorAll('#project-dashboard-tabs [data-bs-toggle="pill"]').forEach(btn => {
                btn.addEventListener('shown.bs.tab', function () {
                    localStorage.setItem(TAB_KEY, this.getAttribute('data-bs-target'));

                    if (window.Plotly) {
                        document.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                    }
                });
            });

            const savedTab = localStorage.getItem(TAB_KEY);
            if (savedTab) {
                const tabEl = document.querySelector('#project-dashboard-tabs [data-bs-target="' + savedTab + '"]');
                if (tabEl) {
                    bootstrap.Tab.getOrCreateInstance(tabEl).show();
                }
            }
        })();
    </script>
@endpush
