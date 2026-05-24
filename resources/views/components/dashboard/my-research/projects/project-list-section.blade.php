@props([
    'id',
    'parentId',
    'title',
    'icon' => 'fas fa-folder',
    'projects' => collect(),
    'badgeClass' => 'text-bg-secondary',
])

@php
    $projects = collect($projects);
    $headingId = "{$id}-heading";
    $collapseId = "{$id}-collapse";
@endphp

<div class="accordion-item border-0 border-bottom">
    <h2 class="accordion-header" id="{{ $headingId }}">
        <button class="accordion-button collapsed px-0 py-2 bg-white shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#{{ $collapseId }}"
                aria-expanded="false"
                aria-controls="{{ $collapseId }}">
            <span class="me-2">
                <i class="{{ $icon }} text-muted"></i>
            </span>
            <span class="flex-grow-1">{{ $title }}</span>
            <span class="badge {{ $badgeClass }} me-2">{{ number_format($projects->count()) }}</span>
        </button>
    </h2>

    <div id="{{ $collapseId }}"
         class="accordion-collapse collapse"
         aria-labelledby="{{ $headingId }}"
         data-bs-parent="#{{ $parentId }}">
        <div class="accordion-body px-0 py-2">
            @forelse($projects->take(8) as $project)
                <div class="d-flex justify-content-between align-items-start border-bottom py-2">
                    <div class="me-2">
                        <a href="{{ route('projects.show', [$project->id]) }}" class="text-decoration-none fw-semibold">
                            {{ $project->name }}
                        </a>
                        <div class="text-muted" style="font-size:.75rem;">
                            {{ number_format($project->file_count ?? 0) }} files ·
                            {{ formatBytes($project->size ?? 0) }}
                        </div>
                    </div>

                    <div class="text-end text-muted" style="font-size:.75rem;">
                        {{ optional($project->updated_at)->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="text-muted py-2" style="font-size:.85rem;">
                    No projects in this category.
                </div>
            @endforelse

            @if($projects->count() > 8)
                <div class="text-muted mt-2" style="font-size:.75rem;">
                    Showing 8 of {{ number_format($projects->count()) }} projects.
                </div>
            @endif
        </div>
    </div>
</div>
