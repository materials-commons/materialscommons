@props([
    'project',
])

@php
    $recentFiles = \App\Models\File::query()
        ->active()
        ->files()
        ->where('project_id', $project->id)
        ->orderByDesc('updated_at')
        ->limit(8)
        ->get(['id', 'name', 'path', 'size', 'updated_at', 'mime_type']);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-clock me-1"></i>Recent Files
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Recently updated files in this project.
                </p>
            </div>

            <a href="{{ route('projects.folders.show', [$project, $project->rootDir]) }}"
               class="btn btn-sm btn-outline-primary">
                Browse Files
            </a>
        </div>

        @if($recentFiles->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-file-upload fa-2x mb-2"></i>
                <div class="fw-semibold">No recent files</div>
                <div style="font-size:.85rem;">Upload files to populate this list.</div>
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach($recentFiles as $file)
                    <a href="{{ route('projects.files.show', [$project, $file]) }}"
                       class="list-group-item list-group-item-action px-0">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="min-width-0">
                                <div class="fw-semibold text-truncate">
                                    <i class="fas fa-file-alt text-muted me-1"></i>{{ $file->name }}
                                </div>

                                <div class="text-muted text-truncate" style="font-size:.78rem;">
                                    {{ $file->path }}
                                </div>
                            </div>

                            <div class="text-end flex-shrink-0">
                                <div class="text-muted" style="font-size:.78rem;">
                                    {{ formatBytes((int) ($file->size ?? 0)) }}
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    {{ $file->updated_at?->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
