@props([
    'project',
    'metrics' => [],
])

@php
    $total = max(1, (int) ($metrics['totalDatasets'] ?? 0));

    $items = collect([
        [
            'label' => 'Published',
            'value' => (int) ($metrics['publishedCount'] ?? 0),
            'color' => 'success',
            'hint' => 'public datasets',
        ],
        [
            'label' => 'Draft',
            'value' => (int) ($metrics['draftCount'] ?? 0),
            'color' => 'warning',
            'hint' => 'unpublished datasets',
        ],
        [
            'label' => 'Private',
            'value' => (int) ($metrics['privateCount'] ?? 0),
            'color' => 'secondary',
            'hint' => 'privately published datasets',
        ],
        [
            'label' => 'Test Published',
            'value' => (int) ($metrics['testPublishedCount'] ?? 0),
            'color' => 'info',
            'hint' => 'test publication state',
        ],
    ]);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-pie me-1"></i>Publication Status
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Publication breakdown for datasets in this project.
                </p>
            </div>

            <span class="badge text-bg-success">
                {{ (int) ($metrics['publishedPercent'] ?? 0) }}% published
            </span>
        </div>

        @if((int) ($metrics['totalDatasets'] ?? 0) === 0)
            <div class="text-center text-muted py-4">
                <i class="fas fa-database fa-2x mb-2"></i>
                <div class="fw-semibold">No datasets yet</div>
                <div style="font-size:.85rem;">Create a dataset to see publication status.</div>
            </div>
        @else
            @foreach($items as $item)
                @php
                    $percent = round(($item['value'] / $total) * 100);
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between gap-2 mb-1">
                        <div class="small text-muted">{{ $item['label'] }}</div>
                        <div class="small fw-semibold">
                            {{ number_format($item['value']) }}
                            <span class="text-muted fw-normal">({{ $percent }}%)</span>
                        </div>
                    </div>

                    <div class="progress" style="height:.45rem;">
                        <div class="progress-bar bg-{{ $item['color'] }}"
                             role="progressbar"
                             style="width: {{ $percent }}%;"
                             aria-valuenow="{{ $percent }}"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>

                    <div class="text-muted mt-1" style="font-size:.72rem;">{{ $item['hint'] }}</div>
                </div>
            @endforeach
        @endif
    </div>
</div>
