@props([
    'project',
])

@php
    $publishedCount = (int) ($project->published_datasets_count ?? 0);
    $draftCount = (int) ($project->unpublished_datasets_count ?? 0);
    $datasetsCount = $publishedCount + $draftCount;
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Datasets"
            :value="$datasetsCount"
            hint="total datasets"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Published"
            :value="$publishedCount"
            hint="public datasets"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Draft"
            :value="$draftCount"
            hint="unpublished"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Needs Metadata"
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
                    <i class="fas fa-database me-1"></i>Datasets Overview
                </h6>
                <p class="text-muted mb-2">
                    Dataset analytics placeholder for published and unpublished datasets, missing metadata,
                    licenses, DOIs, authors, and publishing readiness.
                </p>
            </div>

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

        <div class="row g-3 mt-1">
            <div class="col-12 col-lg-6">
                <div class="border rounded p-3 h-100 text-center">
                    <i class="fas fa-chart-pie text-muted mb-2" style="font-size:2rem;"></i>
                    <h6 class="text-muted">Publication Status</h6>
                    <p class="text-muted mb-0" style="font-size:.82rem;">
                        Placeholder for published versus draft dataset chart.
                    </p>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="border rounded p-3 h-100 text-center">
                    <i class="fas fa-clipboard-check text-muted mb-2" style="font-size:2rem;"></i>
                    <h6 class="text-muted">Dataset Metadata</h6>
                    <p class="text-muted mb-0" style="font-size:.82rem;">
                        Placeholder for license, DOI, author, description, and tag readiness.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
