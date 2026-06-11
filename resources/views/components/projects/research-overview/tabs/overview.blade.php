@props([
    'project',
    'readme' => null,
])

@php
    $datasetsCount = (int) ($project->published_datasets_count ?? 0)
        + (int) ($project->unpublished_datasets_count ?? 0);

    $hasReadme = filled($readme);
    $hasDescription = filled($project->description ?? null) || filled($project->summary ?? null);
@endphp

<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted">
                    <i class="fas fa-info-circle me-1"></i>Project Summary
                </h6>

                <p class="text-muted">
                    This project contains
                    <span class="fw-semibold">{{ number_format($project->file_count) }}</span> files,
                    <span class="fw-semibold">{{ number_format($project->experiments_count) }}</span> studies,
                    <span class="fw-semibold">{{ number_format($project->entities_count) }}</span> samples,
                    and <span class="fw-semibold">{{ number_format($datasetsCount) }}</span> datasets.
                </p>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">Storage</div>
                            <div class="fw-semibold">{{ formatBytes($project->size) }}</div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">Updated</div>
                            <div class="fw-semibold">{{ $project->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <p class="text-muted mt-3 mb-0" style="font-size:.82rem;">
                    Overview analytics placeholder: recent project activity, composition trends,
                    publishing readiness, and metadata coverage.
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

                <div class="list-group list-group-flush">
                    @unless($hasReadme)
                        <div class="list-group-item px-0">
                            <div class="fw-semibold small">Add a project README</div>
                            <div class="text-muted small">Help collaborators understand the project contents.</div>
                        </div>
                    @endunless

                    @unless($hasDescription)
                        <div class="list-group-item px-0">
                            <div class="fw-semibold small">Add a project description</div>
                            <div class="text-muted small">Summarize the purpose and scope of the project.</div>
                        </div>
                    @endunless

                    <div class="list-group-item px-0">
                        <div class="fw-semibold small">Review metadata readiness</div>
                        <div class="text-muted small">Check sample attributes, process attributes, and dataset metadata.</div>
                    </div>

                    <div class="list-group-item px-0">
                        <div class="fw-semibold small">Review project health</div>
                        <div class="text-muted small">Find critical issues, warnings, and cleanup opportunities.</div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <button type="button"
                            class="btn btn-sm btn-outline-warning js-project-dashboard-show-tab"
                            data-tab-target="#tab-project-dashboard-metadata">
                        <i class="fas fa-clipboard-check me-1"></i>Metadata
                    </button>

                    <button type="button"
                            class="btn btn-sm btn-outline-danger js-project-dashboard-show-tab"
                            data-tab-target="#tab-project-dashboard-health">
                        <i class="fas fa-heartbeat me-1"></i>Health
                    </button>

                    <a href="{{ route('projects.upload-files', [$project]) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-upload me-1"></i>Upload Files
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <x-display-markdown-file :file="$readme"></x-display-markdown-file>
</div>
