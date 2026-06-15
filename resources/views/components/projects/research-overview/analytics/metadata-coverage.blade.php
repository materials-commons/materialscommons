@props([
    'project',
])

@php
    $hasDescription = filled($project->description ?? null) || filled($project->summary ?? null);
    $entityAttributesCount = (int) ($project->entityAttributesCount ?? 0);
    $activityAttributesCount = (int) ($project->activityAttributesCount ?? 0);
    $datasetsCount = (int) ($project->published_datasets_count ?? 0)
        + (int) ($project->unpublished_datasets_count ?? 0);

    $checks = collect([
        [
            'label' => 'Project description',
            'complete' => $hasDescription,
            'hint' => $hasDescription ? 'Description is present' : 'Add a project description',
        ],
        [
            'label' => 'Sample attributes',
            'complete' => $entityAttributesCount > 0,
            'hint' => number_format($entityAttributesCount) . ' sample attribute(s)',
        ],
        [
            'label' => 'Process attributes',
            'complete' => $activityAttributesCount > 0,
            'hint' => number_format($activityAttributesCount) . ' process attribute(s)',
        ],
        [
            'label' => 'Dataset pathway',
            'complete' => $datasetsCount > 0,
            'hint' => number_format($datasetsCount) . ' dataset(s)',
        ],
    ]);

    $completeCount = $checks->where('complete', true)->count();
    $readinessPercent = $checks->count() > 0
        ? round(($completeCount / $checks->count()) * 100)
        : 0;

    $readinessColor = match (true) {
        $readinessPercent >= 75 => 'success',
        $readinessPercent >= 50 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-clipboard-check me-1"></i>Metadata Coverage
                </h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">
                    Readiness based on project description, attributes, and dataset preparation.
                </p>
            </div>

            <span class="badge text-bg-{{ $readinessColor }}">
                {{ $readinessPercent }}% ready
            </span>
        </div>

        <div class="progress mb-3" style="height:.65rem;">
            <div class="progress-bar bg-{{ $readinessColor }}"
                 role="progressbar"
                 style="width: {{ $readinessPercent }}%;"
                 aria-valuenow="{{ $readinessPercent }}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>

        @foreach($checks as $check)
            <div class="d-flex align-items-start justify-content-between gap-2 border-bottom py-2">
                <div>
                    <div class="fw-semibold small">{{ $check['label'] }}</div>
                    <div class="text-muted" style="font-size:.75rem;">{{ $check['hint'] }}</div>
                </div>

                <span class="badge text-bg-{{ $check['complete'] ? 'success' : 'warning' }}">
                    {{ $check['complete'] ? 'Ready' : 'Needs work' }}
                </span>
            </div>
        @endforeach

        <div class="d-flex flex-wrap gap-2 mt-3">
            <button type="button"
                    class="btn btn-sm btn-outline-warning js-project-dashboard-show-tab"
                    data-tab-target="#tab-project-dashboard-metadata">
                <i class="fas fa-clipboard-check me-1"></i>Metadata Tab
            </button>

            <a href="{{ route('projects.data-dictionary.entities', [$project]) }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-cubes me-1"></i>Sample Attributes
            </a>

            <a href="{{ route('projects.data-dictionary.activities', [$project]) }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-cogs me-1"></i>Process Attributes
            </a>
        </div>
    </div>
</div>
