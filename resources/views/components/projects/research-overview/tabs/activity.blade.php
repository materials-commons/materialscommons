@props([
    'project',
])

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
            <div>
                <h6 class="card-title text-muted">
                    <i class="fas fa-history me-1"></i>Recent Activity
                </h6>
                <p class="text-muted mb-2">
                    Activity analytics placeholder for recent uploads, study changes, dataset changes,
                    metadata updates, member activity, and project events.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-folder-open me-1"></i>Browse Files
                </a>

                <a href="{{ route('projects.datasets.index', [$project]) }}"
                   class="btn btn-sm btn-outline-success">
                    <i class="fas fa-database me-1"></i>Datasets
                </a>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-12 col-lg-6">
                <div class="border rounded p-3 h-100 text-center">
                    <i class="fas fa-clock text-muted mb-2" style="font-size:2rem;"></i>
                    <h6 class="text-muted">Timeline</h6>
                    <p class="text-muted mb-0" style="font-size:.82rem;">
                        Placeholder for project activity timeline.
                    </p>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="border rounded p-3 h-100 text-center">
                    <i class="fas fa-user-clock text-muted mb-2" style="font-size:2rem;"></i>
                    <h6 class="text-muted">Contributor Activity</h6>
                    <p class="text-muted mb-0" style="font-size:.82rem;">
                        Placeholder for collaborator and contributor activity.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
