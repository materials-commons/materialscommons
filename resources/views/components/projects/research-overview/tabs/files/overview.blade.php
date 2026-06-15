@props([
    'project',
])

@php
    $fileCount = (int) ($project->file_count ?? 0);
    $directoryCount = (int) ($project->directory_count ?? 0);
    $size = (int) ($project->size ?? 0);

    $averageFileSize = $fileCount > 0 ? (int) floor($size / $fileCount) : 0;
    $filesPerFolder = $directoryCount > 0 ? round($fileCount / $directoryCount, 1) : $fileCount;

    $organizationStatus = match (true) {
        $fileCount === 0 => ['label' => 'No files yet', 'color' => 'secondary'],
        $directoryCount === 0 && $fileCount > 25 => ['label' => 'Needs folders', 'color' => 'warning'],
        $filesPerFolder > 100 => ['label' => 'Dense folders', 'color' => 'warning'],
        $filesPerFolder > 25 => ['label' => 'Moderate density', 'color' => 'info'],
        default => ['label' => 'Organized', 'color' => 'success'],
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-folder-open me-1"></i>Files Overview
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Summary of project files, folder organization, storage, and file readiness.
                </p>
            </div>

            <span class="badge text-bg-{{ $organizationStatus['color'] }}">
                {{ $organizationStatus['label'] }}
            </span>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6 col-lg-3">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Files</div>
                    <div class="fw-bold text-primary">{{ number_format($fileCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Folders</div>
                    <div class="fw-bold text-warning">{{ number_format($directoryCount) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Avg File Size</div>
                    <div class="fw-bold text-secondary">{{ formatBytes($averageFileSize) }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small">Files / Folder</div>
                    <div class="fw-bold text-info">{{ number_format($filesPerFolder, 1) }}</div>
                </div>
            </div>
        </div>

        <div class="border rounded p-3 bg-light">
            <div class="fw-semibold small mb-1">
                <i class="fas fa-lightbulb text-warning me-1"></i>Recommended File Action
            </div>

            @if($fileCount === 0)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    This project does not have files yet. Upload files to begin organizing research data.
                </p>
                <a href="{{ route('projects.upload-files', [$project]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-upload me-1"></i>Upload Files
                </a>
            @elseif($directoryCount === 0 && $fileCount > 25)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    This project has many files and no folder structure. Consider organizing files into folders.
                </p>
                <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-folder-open me-1"></i>Browse Files
                </a>
            @elseif($filesPerFolder > 100)
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    Some folders may contain many files. Consider grouping files by study, sample, process, or dataset.
                </p>
                <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-folder-open me-1"></i>Review Folder Structure
                </a>
            @else
                <p class="text-muted mb-2" style="font-size:.85rem;">
                    File organization looks reasonable from aggregate counts. Review recent or largest files below.
                </p>
                <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-folder-open me-1"></i>Browse Files
                </a>
            @endif
        </div>
    </div>
</div>
