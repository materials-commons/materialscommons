@props([
    'project',
    'metrics' => [],
])

@php
    $largestFiles = collect($metrics['largestFiles'] ?? []);
    $largestSize = max(1, (int) ($largestFiles->first()?->size ?? 0));
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-weight-hanging me-1"></i>Largest Files
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Files using the most storage in this project.
                </p>
            </div>

            <span class="badge text-bg-secondary">
                Storage
            </span>
        </div>

        @if($largestFiles->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-hdd fa-2x mb-2"></i>
                <div class="fw-semibold">No file sizes available</div>
                <div style="font-size:.85rem;">Largest files will appear when size metadata is available.</div>
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach($largestFiles as $file)
                    @php
                        $percent = round((((int) $file->size) / $largestSize) * 100);
                    @endphp

                    <a href="{{ route('projects.files.show', [$project, $file->id]) }}"
                       class="list-group-item list-group-item-action px-0">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                            <div class="min-width-0">
                                <div class="fw-semibold text-truncate">
                                    <i class="fas fa-file-alt text-muted me-1"></i>{{ $file->name }}
                                </div>

                                <div class="text-muted text-truncate" style="font-size:.78rem;">
                                    {{ $file->path }}
                                </div>
                            </div>

                            <div class="text-end flex-shrink-0">
                                <div class="fw-semibold" style="font-size:.82rem;">
                                    {{ formatBytes((int) $file->size) }}
                                </div>
                            </div>
                        </div>

                        <div class="progress" style="height:.35rem;">
                            <div class="progress-bar bg-secondary"
                                 role="progressbar"
                                 style="width: {{ $percent }}%;"
                                 aria-valuenow="{{ $percent }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
