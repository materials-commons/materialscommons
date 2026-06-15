@props([
    'project',
])

@php
    $files = \App\Models\File::query()
        ->active()
        ->files()
        ->where('project_id', $project->id)
        ->select(['id', 'name', 'mime_type', 'size'])
        ->get();

    $extensionCounts = $files
        ->map(function ($file) {
            $extension = strtolower(pathinfo((string) $file->name, PATHINFO_EXTENSION));

            return blank($extension) ? 'No extension' : $extension;
        })
        ->countBy()
        ->sortDesc();

    $topExtensions = $extensionCounts->take(8);
    $otherCount = max(0, $extensionCounts->sum() - $topExtensions->sum());

    $totalFiles = max(1, $files->count());

    $mimeCounts = $files
        ->map(fn($file) => blank($file->mime_type ?? null) ? 'Unknown MIME type' : $file->mime_type)
        ->countBy()
        ->sortDesc()
        ->take(5);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-pie me-1"></i>File Type Distribution
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Top file extensions and MIME types in this project.
                </p>
            </div>

            <span class="badge text-bg-primary">
                {{ number_format($files->count()) }} files
            </span>
        </div>

        @if($files->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <div class="fw-semibold">No files available</div>
                <div style="font-size:.85rem;">Upload files to see file type analytics.</div>
            </div>
        @else
            <div class="mb-3">
                @foreach($topExtensions as $extension => $count)
                    @php
                        $percent = round(((int) $count / $totalFiles) * 100);
                    @endphp

                    <div class="mb-2">
                        <div class="d-flex justify-content-between gap-2 mb-1">
                            <div class="small text-muted">
                                <i class="fas fa-file-alt me-1"></i>{{ $extension }}
                            </div>
                            <div class="small fw-semibold">
                                {{ number_format($count) }}
                                <span class="text-muted fw-normal">({{ $percent }}%)</span>
                            </div>
                        </div>

                        <div class="progress" style="height:.45rem;">
                            <div class="progress-bar bg-primary"
                                 role="progressbar"
                                 style="width: {{ $percent }}%;"
                                 aria-valuenow="{{ $percent }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                @endforeach

                @if($otherCount > 0)
                    <div class="d-flex justify-content-between text-muted small border-top pt-2">
                        <span>Other extensions</span>
                        <span>{{ number_format($otherCount) }}</span>
                    </div>
                @endif
            </div>

            <div class="border rounded p-2 bg-light">
                <div class="text-muted small fw-semibold mb-1">Top MIME Types</div>

                @foreach($mimeCounts as $mimeType => $count)
                    <div class="d-flex justify-content-between gap-2" style="font-size:.82rem;">
                        <span class="text-muted text-truncate">{{ $mimeType }}</span>
                        <span class="fw-semibold">{{ number_format($count) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
