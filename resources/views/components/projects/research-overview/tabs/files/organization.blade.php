@props([
    'project',
    'metrics' => [],
])

@php
    $fileCount = (int) ($metrics['filesCount'] ?? 0);
    $directoryCount = (int) ($metrics['foldersCount'] ?? 0);
    $rootFilesCount = (int) ($metrics['rootFilesCount'] ?? 0);
    $rootFilePercent = (int) ($metrics['rootFilePercent'] ?? 0);
    $directoriesWithFilesCount = (int) ($metrics['directoriesWithFilesCount'] ?? 0);
    $emptyFoldersEstimate = (int) ($metrics['emptyFoldersEstimate'] ?? 0);
    $filesPerFolder = (float) ($metrics['filesPerFolder'] ?? 0);

    $organizationScore = 100;

    if ($fileCount === 0) {
        $organizationScore = 0;
    } else {
        if ($directoryCount === 0 && $fileCount > 0) {
            $organizationScore -= 35;
        }

        if ($filesPerFolder > 100) {
            $organizationScore -= 25;
        } elseif ($filesPerFolder > 50) {
            $organizationScore -= 15;
        }

        if ($rootFilePercent > 75) {
            $organizationScore -= 25;
        } elseif ($rootFilePercent > 40) {
            $organizationScore -= 15;
        }

        if ($emptyFoldersEstimate > 25) {
            $organizationScore -= 10;
        }
    }

    $organizationScore = max(0, min(100, $organizationScore));

    $scoreColor = match (true) {
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
                    <i class="fas fa-sitemap me-1"></i>File Organization
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Folder density, root-level files, and organization readiness.
                </p>
            </div>

            <span class="badge text-bg-{{ $scoreColor }}">
                {{ $organizationScore }} score
            </span>
        </div>

        <div class="progress mb-3" style="height:.65rem;">
            <div class="progress-bar bg-{{ $scoreColor }}"
                 role="progressbar"
                 style="width: {{ $organizationScore }}%;"
                 aria-valuenow="{{ $organizationScore }}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="border rounded p-2">
                    <div class="text-muted small">Root Files</div>
                    <div class="fw-bold">{{ number_format($rootFilesCount) }}</div>
                    <div class="text-muted" style="font-size:.72rem;">{{ $rootFilePercent }}% of project files</div>
                </div>
            </div>

            <div class="col-6">
                <div class="border rounded p-2">
                    <div class="text-muted small">Files / Folder</div>
                    <div class="fw-bold">{{ number_format($filesPerFolder, 1) }}</div>
                    <div class="text-muted" style="font-size:.72rem;">density estimate</div>
                </div>
            </div>

            <div class="col-6">
                <div class="border rounded p-2">
                    <div class="text-muted small">Folders With Files</div>
                    <div class="fw-bold">{{ number_format($directoriesWithFilesCount) }}</div>
                    <div class="text-muted" style="font-size:.72rem;">non-root folders</div>
                </div>
            </div>

            <div class="col-6">
                <div class="border rounded p-2">
                    <div class="text-muted small">Empty Folders</div>
                    <div class="fw-bold">{{ number_format($emptyFoldersEstimate) }}</div>
                    <div class="text-muted" style="font-size:.72rem;">estimate</div>
                </div>
            </div>
        </div>

        <div class="border rounded p-2 bg-light">
            <div class="fw-semibold small mb-1">Organization Guidance</div>

            @if($fileCount === 0)
                <div class="text-muted" style="font-size:.82rem;">
                    Upload files to begin organizing this project.
                </div>
            @elseif($rootFilePercent > 75)
                <div class="text-muted" style="font-size:.82rem;">
                    Most files are at the project root. Consider grouping files into folders by study,
                    sample, process, or dataset.
                </div>
            @elseif($filesPerFolder > 100)
                <div class="text-muted" style="font-size:.82rem;">
                    Some folders may be dense. Consider splitting large folders into meaningful subfolders.
                </div>
            @else
                <div class="text-muted" style="font-size:.82rem;">
                    Folder organization looks reasonable from aggregate file counts.
                </div>
            @endif
        </div>
    </div>
</div>
