@props([
    'project',
])

@php
    $fileCount = (int) ($project->file_count ?? 0);
    $directoryCount = (int) ($project->directory_count ?? 0);
    $total = max(1, $fileCount + $directoryCount);

    $organizationScore = match (true) {
        $fileCount === 0 => 0,
        $directoryCount === 0 => 25,
        $fileCount / max(1, $directoryCount) <= 25 => 90,
        $fileCount / max(1, $directoryCount) <= 100 => 70,
        default => 45,
    };

    $organizationColor = match (true) {
        $organizationScore >= 80 => 'success',
        $organizationScore >= 60 => 'info',
        $organizationScore >= 40 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-file-alt me-1"></i>File Organization
                </h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">
                    File and folder balance. File type distribution can be added when extension summaries are available.
                </p>
            </div>

            <span class="badge text-bg-{{ $organizationColor }}">
                {{ $organizationScore }} score
            </span>
        </div>

        <x-projects.research-overview.analytics.metric-row
            label="Files"
            :value="$fileCount"
            :total="$total"
            color="primary"
            hint="active files in the project"
        />

        <x-projects.research-overview.analytics.metric-row
            label="Folders"
            :value="$directoryCount"
            :total="$total"
            color="warning"
            hint="active folders in the project"
        />

        <div class="border rounded p-3 text-center">
            <i class="fas fa-layer-group text-muted mb-2" style="font-size:1.75rem;"></i>
            <h6 class="text-muted">File Type Distribution</h6>
            <p class="text-muted mb-2" style="font-size:.82rem;">
                Ready for extension/MIME summary data: CSV, images, PDFs, notebooks, raw data, and other files.
            </p>

            <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
               class="btn btn-sm btn-outline-primary">
                <i class="fas fa-folder-open me-1"></i>Browse Files
            </a>
        </div>
    </div>
</div>
