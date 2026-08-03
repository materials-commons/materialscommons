<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3 background-white">
        <div class="row g-3">

            {{-- Files column --}}
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-folder text-primary me-2"></i>
                    <strong class="small">Files</strong>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{route('projects.folders.show', [$project, $project->rootDir])}}"
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-folder-open me-1"></i> Browse
                    </a>
                    <a href="{{route('projects.upload-files', [$project])}}"
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-upload me-1"></i> Upload
                    </a>
                    <a href="{{route('projects.globus.uploads.index', [$project])}}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-exchange-alt me-1"></i> Globus
                    </a>
                </div>
            </div>

            {{-- Studies column --}}
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-flask text-info me-2"></i>
                    <strong class="small">Studies</strong>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{route('projects.experiments.index', [$project])}}"
                       class="btn btn-outline-info btn-sm">
                        <i class="fas fa-list me-1"></i> View All
                    </a>
                    <a href="{{route('projects.experiments.create', [$project])}}"
                       class="btn btn-outline-info btn-sm">
                        <i class="fas fa-plus me-1"></i> New Study
                    </a>
                </div>
            </div>

            {{-- Datasets column --}}
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-database text-success me-2"></i>
                    <strong class="small">Datasets</strong>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{route('projects.datasets.index', [$project])}}"
                       class="btn btn-outline-success btn-sm">
                        <i class="fas fa-list me-1"></i> View All
                    </a>
                    <a href="{{route('projects.datasets.create', [$project])}}"
                       class="btn btn-outline-success btn-sm">
                        <i class="fas fa-plus me-1"></i> New Dataset
                    </a>
                    <a href="{{route('projects.datasets.create', [$project])}}"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-file-export me-1"></i> Publish
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
