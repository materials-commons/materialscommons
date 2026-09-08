@props([
    'project',
])

@php
    $datasetsCount = (int) ($project->published_datasets_count ?? 0)
        + (int) ($project->unpublished_datasets_count ?? 0);

    $items = collect([
        [
            'label' => 'Files',
            'value' => (int) ($project->file_count ?? 0),
            'color' => 'primary',
            'hint' => number_format((int) ($project->directory_count ?? 0)) . ' folders',
        ],
        [
            'label' => 'Studies',
            'value' => (int) ($project->experiments_count ?? 0),
            'color' => 'info',
            'hint' => 'experiments in this project',
        ],
        [
            'label' => 'Samples',
            'value' => (int) ($project->entities_count ?? 0),
            'color' => 'success',
            'hint' => 'tracked experimental entities',
        ],
        [
            'label' => 'Datasets',
            'value' => $datasetsCount,
            'color' => 'secondary',
            'hint' => number_format((int) ($project->published_datasets_count ?? 0)) . ' published',
        ],
    ]);

    $total = max(1, $items->sum('value'));
    $dominant = $items->sortByDesc('value')->first();
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-pie me-1"></i>Project Composition
                </h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">
                    Breakdown of the main research objects in this project.
                </p>
            </div>

            <span class="badge text-bg-light text-secondary">
                {{ number_format($items->sum('value')) }} objects
            </span>
        </div>

        @foreach($items as $item)
            <x-projects.research-overview.analytics.metric-row
                :label="$item['label']"
                :value="$item['value']"
                :total="$total"
                :color="$item['color']"
                :hint="$item['hint']"
            />
        @endforeach

        <div class="border rounded p-2 bg-light">
            <div class="text-muted small">Largest category</div>
            <div class="fw-semibold">
                {{ $dominant['label'] ?? 'None' }}
                <span class="text-muted fw-normal">
                    · {{ number_format($dominant['value'] ?? 0) }}
                </span>
            </div>
        </div>
    </div>
</div>
