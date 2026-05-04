@props([
    'projects' => collect(),
    'activeProjects' => collect(),
    'recentlyAccessedProjects' => collect(),
    'archivedProjects' => collect(),
    'deletedProjects' => collect(),
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-chart-pie me-1"></i>Project Analytics
                </h6>
                <p class="text-muted mb-0" style="font-size:.75rem;">
                    Status, activity, storage, and publication-readiness across your research projects.
                </p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-dashboard.my-research.projects.charts.status-chart
                    :projects="$projects"
                    :active-projects="$activeProjects"
                    :archived-projects="$archivedProjects"
                    :deleted-projects="$deletedProjects"
                />
            </div>

            <div class="col-12 col-md-6">
                <x-dashboard.my-research.projects.charts.publication-chart :projects="$projects" />
            </div>

            <div class="col-12 col-md-6">
                <x-dashboard.my-research.projects.charts.activity-chart :projects="$projects" />
            </div>

            <div class="col-12 col-md-6">
                <x-dashboard.my-research.projects.charts.storage-chart :projects="$projects" />
            </div>
        </div>
    </div>
</div>
