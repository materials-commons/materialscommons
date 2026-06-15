@props([
    'project',
])

@php
    $size = (int) ($project->size ?? 0);
    $fileCount = (int) ($project->file_count ?? 0);
    $directoryCount = (int) ($project->directory_count ?? 0);
    $totalNodes = $fileCount + $directoryCount;

    $averageFileSize = $fileCount > 0 ? (int) floor($size / $fileCount) : 0;
    $filesPerFolder = $directoryCount > 0 ? round($fileCount / $directoryCount, 1) : $fileCount;

    $storageBand = match (true) {
        $size >= 1024 * 1024 * 1024 * 100 => 'Very Large',
        $size >= 1024 * 1024 * 1024 * 10 => 'Large',
        $size >= 1024 * 1024 * 1024 => 'Medium',
        $size > 0 => 'Small',
        default => 'Empty',
    };

    $storageColor = match ($storageBand) {
        'Very Large' => 'danger',
        'Large' => 'warning',
        'Medium' => 'info',
        'Small' => 'success',
        default => 'secondary',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-hdd me-1"></i>Storage
                </h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">
                    Aggregate storage and file organization signals.
                </p>
            </div>

            <span class="badge text-bg-{{ $storageColor }}">{{ $storageBand }}</span>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="border rounded p-2 text-center">
                    <div class="text-muted small">Total Size</div>
                    <div class="fw-bold fs-5 text-{{ $storageColor }}">{{ formatBytes($size) }}</div>
                </div>
            </div>

            <div class="col-6">
                <div class="border rounded p-2 text-center">
                    <div class="text-muted small">Avg File Size</div>
                    <div class="fw-bold fs-5 text-secondary">{{ formatBytes($averageFileSize) }}</div>
                </div>
            </div>
        </div>

        <x-projects.research-overview.analytics.metric-row
            label="Files"
            :value="$fileCount"
            :total="max(1, $totalNodes)"
            color="primary"
            hint="active project files"
        />

        <x-projects.research-overview.analytics.metric-row
            label="Folders"
            :value="$directoryCount"
            :total="max(1, $totalNodes)"
            color="warning"
            hint="active project folders"
        />

        <div class="border rounded p-2 bg-light">
            <div class="text-muted small">Organization density</div>
            <div class="fw-semibold">
                {{ number_format($filesPerFolder, 1) }}
                <span class="text-muted fw-normal">files per folder</span>
            </div>
        </div>
    </div>
</div>
