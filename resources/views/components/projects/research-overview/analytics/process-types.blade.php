@props([
    'project',
])

@php
    $studiesCount = (int) ($project->experiments_count ?? 0);
    $samplesCount = (int) ($project->entities_count ?? 0);
    $activityAttributesCount = (int) ($project->activityAttributesCount ?? 0);
    $datasetsCount = (int) ($project->published_datasets_count ?? 0)
        + (int) ($project->unpublished_datasets_count ?? 0);

    $workflowSignals = collect([
        [
            'label' => 'Studies',
            'value' => $studiesCount,
            'complete' => $studiesCount > 0,
            'hint' => 'project experiments',
        ],
        [
            'label' => 'Samples',
            'value' => $samplesCount,
            'complete' => $samplesCount > 0,
            'hint' => 'tracked entities',
        ],
        [
            'label' => 'Process attributes',
            'value' => $activityAttributesCount,
            'complete' => $activityAttributesCount > 0,
            'hint' => 'activity metadata fields',
        ],
        [
            'label' => 'Datasets',
            'value' => $datasetsCount,
            'complete' => $datasetsCount > 0,
            'hint' => 'publishing outputs',
        ],
    ]);

    $completeSignals = $workflowSignals->where('complete', true)->count();
    $workflowReadiness = round(($completeSignals / max(1, $workflowSignals->count())) * 100);

    $workflowColor = match (true) {
        $workflowReadiness >= 75 => 'success',
        $workflowReadiness >= 50 => 'info',
        $workflowReadiness >= 25 => 'warning',
        default => 'danger',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-cogs me-1"></i>Process Readiness
                </h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">
                    Workflow readiness based on studies, samples, process attributes, and datasets.
                </p>
            </div>

            <span class="badge text-bg-{{ $workflowColor }}">
                {{ $workflowReadiness }}% ready
            </span>
        </div>

        <div class="progress mb-3" style="height:.65rem;">
            <div class="progress-bar bg-{{ $workflowColor }}"
                 role="progressbar"
                 style="width: {{ $workflowReadiness }}%;"
                 aria-valuenow="{{ $workflowReadiness }}"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>

        @foreach($workflowSignals as $signal)
            <div class="d-flex align-items-start justify-content-between gap-2 border-bottom py-2">
                <div>
                    <div class="fw-semibold small">{{ $signal['label'] }}</div>
                    <div class="text-muted" style="font-size:.75rem;">{{ $signal['hint'] }}</div>
                </div>

                <div class="text-end">
                    <div class="fw-semibold">{{ number_format($signal['value']) }}</div>
                    <span class="badge text-bg-{{ $signal['complete'] ? 'success' : 'warning' }}">
                        {{ $signal['complete'] ? 'Present' : 'Missing' }}
                    </span>
                </div>
            </div>
        @endforeach

        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('projects.experiments.index', [$project]) }}"
               class="btn btn-sm btn-outline-info">
                <i class="fas fa-flask me-1"></i>Studies
            </a>

            <a href="{{ route('projects.data-dictionary.activities', [$project]) }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-list me-1"></i>Process Attributes
            </a>
        </div>
    </div>
</div>
