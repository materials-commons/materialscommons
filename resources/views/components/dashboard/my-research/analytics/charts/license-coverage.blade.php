@props([
    'licenseCounts' => collect(),
])

@php
    $chartId = 'my-research-license-coverage-chart';

    $licenseCounts = collect($licenseCounts);
    $labels = $licenseCounts->keys()->values();
    $values = $licenseCounts->values();

    $hasData = $values->sum() > 0;
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-0">
            <i class="fas fa-balance-scale me-1"></i>License Coverage
        </h6>
        <p class="text-muted mb-2" style="font-size:.7rem;">
            Dataset license distribution, including missing licenses.
        </p>

        @if($hasData)
            <div id="{{ $chartId }}" style="height:260px;"></div>
        @else
            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                 style="height:260px;">
                No license data
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
                        labels: @json($labels),
                        values: @json($values),
                        type: 'pie',
                        hole: 0.45,
                        textinfo: 'label+value',
                        hoverinfo: 'label+value+percent'
                    }], {
                        paper_bgcolor: 'transparent',
                        plot_bgcolor: 'transparent',
                        font: {family: 'inherit', size: 11},
                        showlegend: true,
                        legend: {orientation: 'h', x: 0.5, xanchor: 'center', y: -0.18},
                        margin: {t: 10, b: 55, l: 10, r: 10}
                    }, {
                        responsive: true,
                        displayModeBar: false
                    });
                });
            })();
        </script>
    @endpush
@endif
