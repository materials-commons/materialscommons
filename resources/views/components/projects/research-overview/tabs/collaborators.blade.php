@props([
    'project',
    'metrics' => [],
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.collaborators.summary-card
            label="Owner"
            :value="$metrics['ownerCount'] ?? 0"
            :hint="$metrics['owner']?->name ?? 'not assigned'"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.collaborators.summary-card
            label="Admins"
            :value="$metrics['adminCount'] ?? 0"
            hint="project admins"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.collaborators.summary-card
            label="Members"
            :value="$metrics['memberCount'] ?? 0"
            hint="project members"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.collaborators.summary-card
            label="Dataset Authors"
            :value="$metrics['datasetAuthorCount'] ?? 0"
            hint="unique contributors"
            color="success"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-projects.research-overview.tabs.collaborators.overview
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-4">
        <x-projects.research-overview.tabs.collaborators.actions :project="$project"/>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.collaborators.team-coverage
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.collaborators.dataset-authors
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.collaborators.team-list
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.collaborators.needs-review
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>
