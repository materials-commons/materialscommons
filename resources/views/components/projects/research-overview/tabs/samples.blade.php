@props([
    'project',
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Samples"
            :value="$project->entities_count"
            hint="tracked entities"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Attributes"
            :value="$project->entityAttributesCount ?? 0"
            hint="sample attributes"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Missing Values"
            value="—"
            hint="placeholder"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Needs Review"
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
                    <i class="fas fa-cubes me-1"></i>Samples Overview
                </h6>
                <p class="text-muted mb-2">
                    Sample analytics placeholder for sample types, attribute coverage,
                    missing values, recently created samples, and metadata completeness.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('projects.entities.index', [$project, 'category' => 'experimental']) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-cubes me-1"></i>View Samples
                </a>

                <a href="{{ route('projects.data-dictionary.entities', [$project]) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-list me-1"></i>Sample Attributes
                </a>
            </div>
        </div>

        <div class="border rounded p-3 mt-2 text-center">
            <i class="fas fa-chart-bar text-muted mb-2" style="font-size:2rem;"></i>
            <h6 class="text-muted">Sample Attribute Coverage</h6>
            <p class="text-muted mb-0" style="font-size:.82rem;">
                Placeholder for sample metadata coverage and missing value analytics.
            </p>
        </div>
    </div>
</div>
