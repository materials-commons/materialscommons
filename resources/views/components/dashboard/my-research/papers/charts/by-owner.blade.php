@props([
    'papersByOwner' => collect(),
])

@php
    $chartId = 'my-research-papers-by-owner-chart-' . uniqid();
    $papersByOwner = collect($papersByOwner)->take(12);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-1">
            <i class="fas fa-user-edit me-1"></i>Papers by Setup User
        </h6>

        <p class="text-muted mb-2" style="font-size:.85rem;">
            Papers grouped by the user who originally set up the paper record.
        </p>

        <div id="{{ $chartId }}" class="js-plotly-plot" style="height:250px;"></div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const el = document.getElementById(@json($chartId));

            if (!el || typeof Plotly === 'undefined') {
                return;
            }

            const labels = @json($papersByOwner->keys()->values());
            const values = @json($papersByOwner->values()->values());

            Plotly.newPlot(el, [{
                type: 'bar',
                x: labels,
                y: values,
                marker: {
                    color: '#6f42c1'
                },
                hovertemplate: '%{x}: %{y} paper(s)<extra></extra>'
            }], {
                margin: { t: 10, b: 80, l: 35, r: 15 },
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: { family: 'inherit', size: 11 },
                showlegend: false,
                yaxis: {
                    tickformat: 'd',
                    dtick: 1
                },
                xaxis: {
                    tickangle: -35
                }
            }, {
                responsive: true,
                displayModeBar: false
            });
        })();
    </script>
@endpush
