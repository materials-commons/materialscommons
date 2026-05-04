@props([
    'projects' => collect(),
])

@php
    $chartId = 'my-research-project-activity-chart';

    $monthKeys = [];
    $monthLabels = [];

    for ($i = 11; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $monthKeys[] = $month->format('Y-m');
        $monthLabels[] = $month->format('M Y');
    }

    $updatesByMonth = collect($projects)
        ->groupBy(fn($project) => \Carbon\Carbon::parse($project->updated_at)->format('Y-m'))
        ->map->count();

    $activityValues = array_map(
        fn($key) => $updatesByMonth->get($key, 0),
        $monthKeys
    );
@endphp

<div>
    <h6 class="text-muted mb-0" style="font-size:.8rem;">
        <i class="fas fa-chart-line me-1"></i>Last Activity
    </h6>
    <div id="{{ $chartId }}" class="js-plotly-plot" style="height:190px;"></div>
</div>

@push('scripts')
    <script>
        (function () {
            const el = document.getElementById(@json($chartId));
            if (!el || typeof Plotly === 'undefined') return;

            Plotly.newPlot(el, [{
                type: 'bar',
                x: @json($monthLabels),
                y: @json($activityValues),
                marker: {
                    color: @json($activityValues),
                    colorscale: [[0, '#cfe2ff'], [1, '#0d6efd']],
                    showscale: false
                },
                hovertemplate: '%{x}: %{y} project(s) updated<extra></extra>'
            }], {
                margin: { t: 10, b: 55, l: 35, r: 10 },
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: { family: 'inherit', size: 11 },
                showlegend: false,
                xaxis: {
                    tickangle: -35,
                    tickfont: { size: 9 }
                },
                yaxis: {
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
