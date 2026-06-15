@props([
    'project',
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.files.summary-card
            label="Files"
            :value="$project->file_count"
            hint="active project files"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.files.summary-card
            label="Folders"
            :value="$project->directory_count"
            hint="directory structure"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.files.summary-card
            label="Storage"
            :value="formatBytes($project->size)"
            hint="total project size"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.files.unlinked-files-summary :project="$project"/>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-projects.research-overview.tabs.files.overview :project="$project"/>
    </div>

    <div class="col-12 col-xl-4">
        <x-projects.research-overview.tabs.files.actions :project="$project"/>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.files.file-types :project="$project"/>
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.files.organization :project="$project"/>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.files.recent-files :project="$project"/>
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.files.largest-files :project="$project"/>
    </div>
</div>
