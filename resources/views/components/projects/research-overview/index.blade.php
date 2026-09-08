@props([
    'project',
    'readme',
])

@php
    $projectDashboardTabKey = 'mc_project_dashboard_tab_' . $project->id;
@endphp

<x-projects.research-overview.header :project="$project"/>

<x-projects.research-overview.kpi :project="$project"/>

<x-projects.research-overview.needs-attention :project="$project" :readme="$readme"/>

<x-projects.research-overview.analytics.overview :project="$project"/>

@if(!is_null($readme))
    <livewire:projects.research-overview.tabs
        :project="$project"
        :readme="$readme"
        :key="'project-research-overview-tabs-' . $project->id"
    />
@else
    <livewire:projects.research-overview.tabs
        :project="$project"
        :key="'project-research-overview-tabs-' . $project->id"
    />
@endif
