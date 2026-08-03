@props([
    'project',
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-bolt me-1"></i>File Actions
        </h6>

        <p class="text-muted" style="font-size:.85rem;">
            Common actions for adding, organizing, transferring, and publishing project files.
        </p>

        <div class="d-grid gap-2">
            <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
               class="btn btn-sm btn-outline-primary text-start">
                <i class="fas fa-folder-open me-1"></i>Browse Files
            </a>

            <a href="{{ route('projects.upload-files', [$project]) }}"
               class="btn btn-sm btn-outline-primary text-start">
                <i class="fas fa-upload me-1"></i>Upload Files
            </a>

            <a href="{{ route('projects.globus.uploads.index', [$project]) }}"
               class="btn btn-sm btn-outline-secondary text-start">
                <i class="fas fa-exchange-alt me-1"></i>Globus Transfers
            </a>

            <a href="{{ route('projects.experiments.create', [$project]) }}"
               class="btn btn-sm btn-outline-info text-start">
                <i class="fas fa-flask me-1"></i>Create Study
            </a>

            <a href="{{ route('projects.datasets.create', [$project]) }}"
               class="btn btn-sm btn-outline-success text-start">
                <i class="fas fa-database me-1"></i>Create Dataset
            </a>
        </div>
    </div>
</div>
