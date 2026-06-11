@props([
    'project',
])

@php
    $healthLabel = match ($project->health) {
        'critical' => 'Critical',
        'warning' => 'Warning',
        null => 'Unknown',
        default => 'Healthy',
    };

    $healthColor = match ($project->health) {
        'critical' => 'danger',
        'warning' => 'warning',
        null => 'secondary',
        default => 'success',
    };
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Status"
            :value="$healthLabel"
            hint="current health"
            :color="$healthColor"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Critical"
            value="—"
            hint="placeholder"
            color="danger"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Warnings"
            value="—"
            hint="placeholder"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.summary-card
            label="Last Check"
            value="—"
            hint="placeholder"
            color="secondary"
        />
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
            <div>
                <h6 class="card-title text-muted">
                    <i class="fas fa-heartbeat me-1"></i>Health
                </h6>
                <p class="text-muted mb-2">
                    Health analytics placeholder for project health status, critical issues, warnings,
                    last health check, cleanup opportunities, and recommended fixes.
                </p>
            </div>

            <a href="{{ route('projects.health-reports.index', [$project]) }}"
               class="btn btn-sm btn-outline-{{ $healthColor }}">
                <i class="fas fa-heartbeat me-1"></i>Open Health Reports
            </a>
        </div>

        <div class="border rounded p-3 mt-2 text-center">
            <i class="fas fa-stethoscope text-muted mb-2" style="font-size:2rem;"></i>
            <h6 class="text-muted">Health Report Trends</h6>
            <p class="text-muted mb-0" style="font-size:.82rem;">
                Placeholder for health report trends, recurring issues, and resolved issues.
            </p>
        </div>
    </div>
</div>
