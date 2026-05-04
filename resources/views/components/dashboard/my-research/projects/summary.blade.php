@props([
    'projects' => collect(),
    'totalFiles' => 0,
    'totalSize' => 0,
    'totalMembers' => 0,
    'totalPublishedDatasets' => 0,
])

@php
    $projects = collect($projects);

    $averageMetadataCompleteness = $projects->count() === 0
        ? 0
        : round($projects->avg(function ($project) {
            $fields = [
                !blank($project->name ?? null),
                !blank($project->description ?? null),
                (int) ($project->file_count ?? 0) > 0,
                (int) ($project->published_datasets_count ?? 0) > 0 || (int) ($project->unpublished_datasets_count ?? 0) > 0,
                optional($project->team?->members)->count() + optional($project->team?->admins)->count() > 0,
            ];

            return collect($fields)->filter()->count() / count($fields) * 100;
        }));
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-2">
            <i class="fas fa-list-check me-1"></i>Project-Level Statistics
        </h6>

        <div class="list-group list-group-flush">
            <div class="list-group-item px-0 d-flex justify-content-between">
                <span class="text-muted">Files</span>
                <strong>{{ number_format($totalFiles) }}</strong>
            </div>

            <div class="list-group-item px-0 d-flex justify-content-between">
                <span class="text-muted">Size</span>
                <strong>{{ formatBytes($totalSize) }}</strong>
            </div>

            <div class="list-group-item px-0 d-flex justify-content-between">
                <span class="text-muted">Members</span>
                <strong>{{ number_format($totalMembers) }}</strong>
            </div>

            <div class="list-group-item px-0 d-flex justify-content-between">
                <span class="text-muted">Published datasets</span>
                <strong>{{ number_format($totalPublishedDatasets) }}</strong>
            </div>

            <div class="list-group-item px-0">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Metadata completeness</span>
                    <strong>{{ $averageMetadataCompleteness }}%</strong>
                </div>
                <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-success"
                         role="progressbar"
                         style="width: {{ $averageMetadataCompleteness }}%;"
                         aria-valuenow="{{ $averageMetadataCompleteness }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
