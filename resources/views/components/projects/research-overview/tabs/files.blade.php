@props([
    'project',
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Files"
            :value="$project->file_count"
            hint="active project files"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Folders"
            :value="$project->directory_count"
            hint="directory structure"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Storage"
            :value="formatBytes($project->size)"
            hint="total project size"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Unlinked"
            value="—"
            hint="placeholder"
            color="danger"
        />
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
            <div>
                <h6 class="card-title text-muted">
                    <i class="fas fa-folder-open me-1"></i>Files Overview
                </h6>
                <p class="text-muted mb-2">
                    File analytics placeholder for recent uploads, largest files, file type distribution,
                    storage growth, and files not yet connected to studies or datasets.
                </p>
            </div>

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

        <div class="row g-3 mt-1">
            <div class="col-12 col-lg-6">
                <div class="border rounded p-3 h-100 text-center">
                    <i class="fas fa-chart-pie text-muted mb-2" style="font-size:2rem;"></i>
                    <h6 class="text-muted">File Type Distribution</h6>
                    <p class="text-muted mb-0" style="font-size:.82rem;">
                        Placeholder for extension and MIME type analytics.
                    </p>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="border rounded p-3 h-100 text-center">
                    <i class="fas fa-clock text-muted mb-2" style="font-size:2rem;"></i>
                    <h6 class="text-muted">Recent Uploads</h6>
                    <p class="text-muted mb-0" style="font-size:.82rem;">
                        Placeholder for recently uploaded and recently modified files.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
