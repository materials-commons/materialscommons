@props([
    'project',
    'metrics' => [],
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.samples.summary-card
            label="Samples"
            :value="$metrics['totalSamples'] ?? 0"
            hint="tracked entities"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.samples.summary-card
            label="Measurements"
            :value="$metrics['totalMeasurements'] ?? 0"
            hint="entity states"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.samples.summary-card
            label="Processes"
            :value="$metrics['totalProcesses'] ?? 0"
            hint="sample links"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.samples.summary-card
            label="Needs Review"
            :value="collect($metrics['samplesNeedingReview'] ?? [])->count()"
            hint="missing context"
            color="{{ collect($metrics['samplesNeedingReview'] ?? [])->count() > 0 ? 'warning' : 'success' }}"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-projects.research-overview.tabs.samples.overview
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-4">
        <x-projects.research-overview.tabs.samples.actions :project="$project"/>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.samples.coverage
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.samples.metadata-readiness
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.samples.category-distribution
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.samples.needs-review
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>

<x-projects.research-overview.tabs.samples.recent-samples
    :project="$project"
    :metrics="$metrics"
/>
