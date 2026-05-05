@props([
    'papersByProject' => collect(),
])

@php
    $chartId = 'my-research-papers-by-project-chart-' . uniqid();
    $papersByProject = collect($papersByProject)->take(12);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-1">
            <i class="fas fa-folder-open me-1"></i>Papers by Project
        </h6>

        <p class="text-muted mb-2" style="font-size:.85rem;">
            Project association is inferred from the datasets linked to each paper.
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

            const labels = @json($papersByProject->keys()->values());
            const values = @json($papersByProject->values()->values());

            Plotly.newPlot(el, [{
                type: 'bar',
                orientation: 'h',
                x: values,
                y: labels,
                marker: {
                    color: '#0d6efd'
                },
                hovertemplate: '%{y}: %{x} paper(s)<extra></extra>'
            }], {
                margin: { t: 10, b: 35, l: 120, r: 15 },
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: { family: 'inherit', size: 11 },
                showlegend: false,
                xaxis: {
                    tickformat: 'd',
                    dtick: 1
                }
            }, {
                responsive: true,
                displayModeBar: false
            });
        })();
    </script>
@endpush
