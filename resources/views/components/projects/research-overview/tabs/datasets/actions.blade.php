@props([
    'project',
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-bolt me-1"></i>Dataset Actions
        </h6>

        <p class="text-muted" style="font-size:.85rem;">
            Common actions for reviewing, creating, and preparing datasets for publication.
        </p>

        <div class="d-grid gap-2">
            <a href="{{ route('projects.datasets.index', [$project]) }}"
               class="btn btn-sm btn-outline-success text-start">
                <i class="fas fa-list me-1"></i>View Datasets
            </a>

            <a href="{{ route('projects.datasets.create', [$project]) }}"
               class="btn btn-sm btn-outline-success text-start">
                <i class="fas fa-plus me-1"></i>Create Dataset
            </a>

            <a href="{{ route('projects.experiments.index', [$project]) }}"
               class="btn btn-sm btn-outline-info text-start">
                <i class="fas fa-flask me-1"></i>Review Studies
            </a>

            <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
               class="btn btn-sm btn-outline-primary text-start">
                <i class="fas fa-folder-open me-1"></i>Browse Files
            </a>

            <button type="button"
                    class="btn btn-sm btn-outline-warning text-start js-project-dashboard-show-tab"
                    data-tab-target="#tab-project-dashboard-metadata">
                <i class="fas fa-clipboard-check me-1"></i>Review Metadata
            </button>
        </div>
    </div>
</div>
