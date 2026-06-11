@php
    $projectDashboardTabKey = 'mc_project_dashboard_tab_' . $project->id;
@endphp

<x-projects.research-overview.header :project="$project"/>

<x-projects.research-overview.kpi :project="$project"/>

<x-projects.research-overview.needs-attention :project="$project" :readme="$readme"/>

<x-projects.research-overview.analytics.overview :project="$project"/>

<x-projects.research-overview.tabs :project="$project"/>

<x-projects.research-overview.tab-content :project="$project" :readme="$readme"/>

<x-projects.research-overview.tab-persistence :project="$project" :tab-key="$projectDashboardTabKey"/>
