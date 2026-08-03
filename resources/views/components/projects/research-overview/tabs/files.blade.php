@props([
    'project',
    'metrics' => [],
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.files.summary-card
            label="Files"
            :value="$metrics['filesCount'] ?? 0"
            hint="active project files"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.files.summary-card
            label="Folders"
            :value="$metrics['foldersCount'] ?? 0"
            hint="directory structure"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.files.summary-card
            label="Storage"
            :value="formatBytes((int) ($metrics['filesSize'] ?? 0))"
            hint="total project file size"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3">
        <x-projects.research-overview.tabs.files.summary-card
            label="Root Files"
            :value="$metrics['rootFilesCount'] ?? 0"
            :hint="($metrics['rootFilePercent'] ?? 0) . '% at root'"
            color="{{ ((int) ($metrics['rootFilesCount'] ?? 0)) > 0 ? 'warning' : 'success' }}"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <x-projects.research-overview.tabs.files.overview
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-4">
        <x-projects.research-overview.tabs.files.actions :project="$project"/>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.files.file-types
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.files.organization
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.files.recent-files
            :project="$project"
            :metrics="$metrics"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-projects.research-overview.tabs.files.largest-files
            :project="$project"
            :metrics="$metrics"
        />
    </div>
</div>
