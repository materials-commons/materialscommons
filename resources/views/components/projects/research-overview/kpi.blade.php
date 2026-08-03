@props([
    'project',
])

@php
    $membersCount = collect($project->team?->members ?? collect())->count();
    $adminsCount = collect($project->team?->admins ?? collect())->count();

    $datasetsCount = (int) ($project->published_datasets_count ?? 0)
        + (int) ($project->unpublished_datasets_count ?? 0);
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl-2">
        <x-projects.research-overview.summary-card
            label="Files"
            :value="$project->file_count"
            :hint="number_format($project->directory_count) . ' folders'"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-projects.research-overview.summary-card
            label="Studies"
            :value="$project->experiments_count"
            hint="experiments"
            color="info"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-projects.research-overview.summary-card
            label="Samples"
            :value="$project->entities_count"
            hint="tracked entities"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-projects.research-overview.summary-card
            label="Datasets"
            :value="$datasetsCount"
            :hint="number_format($project->published_datasets_count) . ' published'"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-projects.research-overview.summary-card
            label="Storage"
            :value="formatBytes($project->size)"
            hint="total used"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-projects.research-overview.summary-card
            label="Members"
            :value="$membersCount"
            :hint="number_format($adminsCount) . ' admin(s)'"
            color="primary"
        />
    </div>
</div>
