@props([
    'projects' => collect(),
    'activeProjects' => collect(),
    'recentlyAccessedProjects' => collect(),
    'archivedProjects' => collect(),
    'deletedProjects' => collect(),
])

@php
    $projects = collect($projects);
    $activeProjects = collect($activeProjects);
    $recentlyAccessedProjects = collect($recentlyAccessedProjects);
    $archivedProjects = collect($archivedProjects);
    $deletedProjects = collect($deletedProjects);

    $projectsWithUnpublishedData = $projects->filter(fn($project) => ($project->unpublished_datasets_count ?? 0) > 0);
    $projectsWithPublishedDatasets = $projects->filter(fn($project) => ($project->published_datasets_count ?? 0) > 0);

    $totalFiles = $projects->sum(fn($project) => (int) ($project->file_count ?? 0));
    $totalSize = $projects->sum(fn($project) => (int) ($project->size ?? 0));
    $totalMembers = $projects->sum(fn($project) => optional($project->team?->members)->count() + optional($project->team?->admins)->count());
    $totalPublishedDatasets = $projects->sum(fn($project) => (int) ($project->published_datasets_count ?? 0));
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Active</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($activeProjects->count()) }}</div>
            <div class="text-muted" style="font-size:.65rem;">pinned projects</div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Recent</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($recentlyAccessedProjects->count()) }}</div>
            <div class="text-muted" style="font-size:.65rem;">recently accessed</div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Unpublished</div>
            <div class="fw-bold fs-5 text-warning">{{ number_format($projectsWithUnpublishedData->count()) }}</div>
            <div class="text-muted" style="font-size:.65rem;">projects with drafts</div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Published</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($projectsWithPublishedDatasets->count()) }}</div>
            <div class="text-muted" style="font-size:.65rem;">with datasets</div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Archived</div>
            <div class="fw-bold fs-5 text-secondary">{{ number_format($archivedProjects->count()) }}</div>
            <div class="text-muted" style="font-size:.65rem;">not active</div>
        </div>
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Deleted</div>
            <div class="fw-bold fs-5 text-danger">{{ number_format($deletedProjects->count()) }}</div>
            <div class="text-muted" style="font-size:.65rem;">in trash</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
        <x-dashboard.my-research.projects.analytics
            :projects="$projects"
            :active-projects="$activeProjects"
            :recently-accessed-projects="$recentlyAccessedProjects"
            :archived-projects="$archivedProjects"
            :deleted-projects="$deletedProjects"
        />
    </div>

    <div class="col-12 col-lg-4">
        <x-dashboard.my-research.projects.summary
            :total-files="$totalFiles"
            :total-size="$totalSize"
            :total-members="$totalMembers"
            :total-published-datasets="$totalPublishedDatasets"
            :projects="$projects"
        />
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-7">
        <x-dashboard.my-research.projects.table :projects="$projects" />
    </div>

    <div class="col-12 col-xl-5">
        <x-dashboard.my-research.projects.lists
            :active-projects="$activeProjects"
            :recently-accessed-projects="$recentlyAccessedProjects"
            :projects-with-unpublished-data="$projectsWithUnpublishedData"
            :projects-with-published-datasets="$projectsWithPublishedDatasets"
            :archived-projects="$archivedProjects"
            :deleted-projects="$deletedProjects"
        />
    </div>
</div>
