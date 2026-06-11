@props([
    'project',
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Processes"
            value="—"
            hint="placeholder"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Attributes"
            :value="$project->activityAttributesCount ?? 0"
            hint="process attributes"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Types"
            value="—"
            hint="placeholder"
            color="info"
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
                    <i class="fas fa-cogs me-1"></i>Processes Overview
                </h6>
                <p class="text-muted mb-2">
                    Process analytics placeholder for process/activity types, process attributes,
                    missing values, recently created processes, and workflow coverage.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('projects.data-dictionary.activities', [$project]) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-list me-1"></i>Process Attributes
                </a>
            </div>
        </div>

        <div class="border rounded p-3 mt-2 text-center">
            <i class="fas fa-project-diagram text-muted mb-2" style="font-size:2rem;"></i>
            <h6 class="text-muted">Process Type Distribution</h6>
            <p class="text-muted mb-0" style="font-size:.82rem;">
                Placeholder for process type and workflow analytics.
            </p>
        </div>
    </div>
</div>
