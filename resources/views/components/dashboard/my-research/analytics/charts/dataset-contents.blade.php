@props([
    'datasetCounts' => [],
])

@php
    $chartId = 'my-research-dataset-contents-chart';

    $datasetCounts = collect($datasetCounts);
    $labels = $datasetCounts->keys()->values();
    $values = $datasetCounts->values();

    $hasData = $values->sum() > 0;
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-0">
            <i class="fas fa-cubes me-1"></i>Dataset Contents
        </h6>
        <p class="text-muted mb-2" style="font-size:.7rem;">
            Aggregate counts from dataset summary columns.
        </p>

        @if($hasData)
            <div id="{{ $chartId }}" style="height:260px;"></div>
        @else
            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                 style="height:260px;">
                No dataset content data
            </div>
        @endif
    </div>
</div>

@if($hasData)
    @push('scripts')
        <script>
            (function () {
                window.addEventListener('mc:my-research-analytics:render', () => {
                    const div = document.getElementById(@json($chartId));
                    if (!div || !window.Plotly) return;

                    Plotly.newPlot(div, [{
                        x: @json($labels),
                        y: @json($values),
                        type: 'bar',
                        marker: {color: '#0dcaf0'},
                        text: @json($values->map(fn ($value) => number_format($value))->values()),
                        textposition: 'auto',
                        hovertemplate: '%{x}: %{y:,}<extra></extra>'
                    }], {
                        paper_bgcolor: 'transparent',
                        plot_bgcolor: 'transparent',
                        font: {family: 'inherit', size: 11},
                        showlegend: false,
                        margin: {t: 10, b: 45, l: 45, r: 10},
                        yaxis: {
                            rangemode: 'tozero',
                            tickformat: ',d',
                            gridcolor: '#dee2e6'
                        }
                    }, {
                        responsive: true,
                        displayModeBar: false
                    });
                });
            })();
        </script>
    @endpush
@endif
