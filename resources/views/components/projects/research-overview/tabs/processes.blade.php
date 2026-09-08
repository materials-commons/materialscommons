@props([
    'project',
    'metrics' => [],
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.processes.summary-card
            label="Processes"
            :value="$metrics['totalProcesses'] ?? 0"
            hint="activities"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.processes.summary-card
            label="Attributes"
            :value="$metrics['totalAttributes'] ?? 0"
            hint="metadata fields"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.processes.summary-card
            label="Types"
            :value="collect($metrics['typeCounts'] ?? [])->count()"
            hint="process types"
            color="info"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.processes.summary-card
            label="Needs Review"
            :value="collect($metrics['processesNeedingReview'] ?? [])->count()"
            hint="missing context"
            color="{{ collect($metrics['processesNeedingReview'] ?? [])->count() > 0 ? 'warning' : 'success' }}"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-projects.research-overview.tabs.processes.overview
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-4">
        <x-projects.research-overview.tabs.processes.actions :project="$project"/>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.processes.coverage
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.processes.metadata-readiness
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.processes.type-distribution
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.processes.needs-review
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>

<x-projects.research-overview.tabs.processes.recent-processes
    :project="$project"
    :metrics="$metrics"
/>
