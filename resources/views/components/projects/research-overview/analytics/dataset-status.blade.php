@props([
    'project',
])

@php
    $publishedCount = (int) ($project->published_datasets_count ?? 0);
    $draftCount = (int) ($project->unpublished_datasets_count ?? 0);
    $total = $publishedCount + $draftCount;

    $publishedPercent = $total > 0 ? round(($publishedCount / $total) * 100) : 0;
    $draftPercent = $total > 0 ? round(($draftCount / $total) * 100) : 0;
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-database me-1"></i>Dataset Status
                </h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">
                    Publication state for datasets created from this project.
                </p>
            </div>

            <span class="badge text-bg-success">
                {{ $publishedPercent }}% published
            </span>
        </div>

        @if($total === 0)
            <div class="text-center text-muted py-3">
                <i class="fas fa-database fa-2x mb-2"></i>
                <div class="fw-semibold">No datasets yet</div>
                <div style="font-size:.82rem;">
                    Create a dataset when files, samples, and metadata are ready to publish.
                </div>
            </div>
        @else
            <x-projects.research-overview.analytics.metric-row
                label="Published"
                :value="$publishedCount"
                :total="$total"
                color="success"
                hint="public datasets available outside the project"
            />

            <x-projects.research-overview.analytics.metric-row
                label="Draft"
                :value="$draftCount"
                :total="$total"
                color="warning"
                hint="unpublished datasets that may need review"
            />

            <div class="row g-2">
                <div class="col-6">
                    <div class="border rounded p-2 text-center">
                        <div class="text-muted small">Published</div>
                        <div class="fw-bold text-success fs-5">{{ $publishedPercent }}%</div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="border rounded p-2 text-center">
                        <div class="text-muted small">Draft</div>
                        <div class="fw-bold text-warning fs-5">{{ $draftPercent }}%</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('projects.datasets.index', [$project]) }}"
               class="btn btn-sm btn-outline-success">
                <i class="fas fa-list me-1"></i>Review Datasets
            </a>

            <a href="{{ route('projects.datasets.create', [$project]) }}"
               class="btn btn-sm btn-outline-success">
                <i class="fas fa-plus me-1"></i>New Dataset
            </a>
        </div>
    </div>
</div>
