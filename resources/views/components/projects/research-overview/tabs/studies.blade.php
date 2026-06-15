@props([
    'project',
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.studies.summary-card
            label="Studies"
            :value="$project->experiments_count"
            hint="experiments"
            color="info"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.studies.with-files-summary :project="$project"/>
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.studies.with-samples-summary :project="$project"/>
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.studies.needs-review-summary :project="$project"/>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-projects.research-overview.tabs.studies.overview :project="$project"/>
    </div>

    <div class="col-12 col-xl-4">
        <x-projects.research-overview.tabs.studies.actions :project="$project"/>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.studies.coverage :project="$project"/>
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.studies.needs-review :project="$project"/>
    </div>

    <div class="col-12">
        <x-projects.research-overview.tabs.studies.recent-studies :project="$project"/>
    </div>
</div>
