@props([
    'project',
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Studies"
            :value="$project->experiments_count"
            hint="experiments"
            color="info"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="With Files"
            value="—"
            hint="placeholder"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="With Samples"
            value="—"
            hint="placeholder"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Needs Review"
            value="—"
            hint="placeholder"
            color="warning"
        />
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
            <div>
                <h6 class="card-title text-muted">
                    <i class="fas fa-flask me-1"></i>Studies Overview
                </h6>
                <p class="text-muted mb-2">
                    Study analytics placeholder for recent studies, studies missing files,
                    studies missing samples, and study-to-dataset readiness.
                </p>
            </div>

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

        <div class="border rounded p-3 mt-2 text-center">
            <i class="fas fa-project-diagram text-muted mb-2" style="font-size:2rem;"></i>
            <h6 class="text-muted">Study Coverage</h6>
            <p class="text-muted mb-0" style="font-size:.82rem;">
                Placeholder for study coverage analytics across files, samples, processes, and datasets.
            </p>
        </div>
    </div>
</div>
