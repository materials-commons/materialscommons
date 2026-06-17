@props([
    'project',
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-bolt me-1"></i>Collaborator Actions
        </h6>

        <p class="text-muted" style="font-size:.85rem;">
            Common actions for managing project members and reviewing dataset contributor metadata.
        </p>

        <div class="d-grid gap-2">
            <a href="{{ route('projects.users.index', [$project]) }}"
               class="btn btn-sm btn-outline-primary text-start">
                <i class="fas fa-users me-1"></i>Manage Members
            </a>

            <a href="{{ route('projects.datasets.index', [$project]) }}"
               class="btn btn-sm btn-outline-success text-start">
                <i class="fas fa-database me-1"></i>Review Datasets
            </a>

            <button type="button"
                    class="btn btn-sm btn-outline-warning text-start js-project-dashboard-show-tab"
                    data-tab-target="#tab-project-dashboard-datasets">
                <i class="fas fa-clipboard-check me-1"></i>Dataset Metadata
            </button>

            <button type="button"
                    class="btn btn-sm btn-outline-secondary text-start js-project-dashboard-show-tab"
                    data-tab-target="#tab-project-dashboard-activity">
                <i class="fas fa-history me-1"></i>Review Activity
            </button>
        </div>
    </div>
</div>
