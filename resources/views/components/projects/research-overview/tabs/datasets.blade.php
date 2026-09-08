@props([
    'project',
    'metrics' => [],
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.datasets.summary-card
            label="Datasets"
            :value="$metrics['totalDatasets'] ?? 0"
            hint="total datasets"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.datasets.summary-card
            label="Published"
            :value="$metrics['publishedCount'] ?? 0"
            :hint="($metrics['publishedPercent'] ?? 0) . '% published'"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.datasets.summary-card
            label="Draft"
            :value="$metrics['draftCount'] ?? 0"
            hint="unpublished"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.datasets.summary-card
            label="Needs Metadata"
            :value="collect($metrics['needsMetadata'] ?? [])->count()"
            hint="missing key fields"
            color="{{ collect($metrics['needsMetadata'] ?? [])->count() > 0 ? 'danger' : 'success' }}"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-projects.research-overview.tabs.datasets.overview
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-4">
        <x-projects.research-overview.tabs.datasets.actions :project="$project"/>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.datasets.publication-status
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.datasets.metadata-readiness
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.datasets.contents
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.datasets.license-coverage
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.datasets.needs-review
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.datasets.recent-datasets
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>
