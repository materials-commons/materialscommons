@props([
    'project',
    'readme' => null,
])

<div class="tab-content" id="project-dashboard-tabs-content">
    <div class="tab-pane fade show active"
         id="tab-project-dashboard-overview"
         role="tabpanel"
         aria-labelledby="project-dashboard-overview-tab">
        <x-projects.research-overview.tabs.overview :project="$project" :readme="$readme"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-files"
         role="tabpanel"
         aria-labelledby="project-dashboard-files-tab">
        <x-projects.research-overview.tabs.files :project="$project"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-studies"
         role="tabpanel"
         aria-labelledby="project-dashboard-studies-tab">
        <x-projects.research-overview.tabs.studies :project="$project"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-datasets"
         role="tabpanel"
         aria-labelledby="project-dashboard-datasets-tab">
        <x-projects.research-overview.tabs.datasets :project="$project"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-samples"
         role="tabpanel"
         aria-labelledby="project-dashboard-samples-tab">
        <x-projects.research-overview.tabs.samples :project="$project"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-processes"
         role="tabpanel"
         aria-labelledby="project-dashboard-processes-tab">
        <x-projects.research-overview.tabs.processes :project="$project"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-metadata"
         role="tabpanel"
         aria-labelledby="project-dashboard-metadata-tab">
        <x-projects.research-overview.tabs.metadata :project="$project" :readme="$readme"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-collaborators"
         role="tabpanel"
         aria-labelledby="project-dashboard-collaborators-tab">
        <x-projects.research-overview.tabs.collaborators :project="$project"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-health"
         role="tabpanel"
         aria-labelledby="project-dashboard-health-tab">
        <x-projects.research-overview.tabs.health :project="$project"/>
    </div>

    <div class="tab-pane fade"
         id="tab-project-dashboard-activity"
         role="tabpanel"
         aria-labelledby="project-dashboard-activity-tab">
        <x-projects.research-overview.tabs.activity :project="$project"/>
    </div>
</div>
