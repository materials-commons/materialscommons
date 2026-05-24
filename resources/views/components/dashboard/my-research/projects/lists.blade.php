@props([
    'activeProjects' => collect(),
    'recentlyAccessedProjects' => collect(),
    'projectsWithUnpublishedData' => collect(),
    'projectsWithPublishedDatasets' => collect(),
    'archivedProjects' => collect(),
    'deletedProjects' => collect(),
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-lightbulb me-1"></i>Helpful Project Lists
        </h6>

        <div class="accordion" id="my-research-project-lists">
            <x-dashboard.my-research.projects.project-list-section
                id="active-projects"
                parent-id="my-research-project-lists"
                title="Active Projects"
                icon="fas fa-star"
                :projects="$activeProjects"
                badge-class="text-bg-primary"
            />

            <x-dashboard.my-research.projects.project-list-section
                id="recent-projects"
                parent-id="my-research-project-lists"
                title="Recently Accessed Projects"
                icon="fas fa-clock"
                :projects="$recentlyAccessedProjects"
                badge-class="text-bg-info"
            />

            <x-dashboard.my-research.projects.project-list-section
                id="unpublished-projects"
                parent-id="my-research-project-lists"
                title="Projects With Unpublished Data"
                icon="fas fa-triangle-exclamation"
                :projects="$projectsWithUnpublishedData"
                badge-class="text-bg-warning"
            />

            <x-dashboard.my-research.projects.project-list-section
                id="published-projects"
                parent-id="my-research-project-lists"
                title="Projects With Published Datasets"
                icon="fas fa-database"
                :projects="$projectsWithPublishedDatasets"
                badge-class="text-bg-success"
            />

            <x-dashboard.my-research.projects.project-list-section
                id="archived-projects"
                parent-id="my-research-project-lists"
                title="Archived Projects"
                icon="fas fa-box-archive"
                :projects="$archivedProjects"
                badge-class="text-bg-secondary"
            />

            <x-dashboard.my-research.projects.project-list-section
                id="deleted-projects"
                parent-id="my-research-project-lists"
                title="Deleted Projects"
                icon="fas fa-trash"
                :projects="$deletedProjects"
                badge-class="text-bg-danger"
            />
        </div>
    </div>
</div>
