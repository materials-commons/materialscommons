@props([
    'projects' => collect(),
])

@php
    $chartId = 'my-research-project-storage-chart';

    $topProjects = collect($projects)
        ->sortByDesc(fn($project) => (int) ($project->size ?? 0))
        ->take(8)
        ->values();

    $names = $topProjects
        ->pluck('name')
        ->map(fn($name) => strlen($name) > 20 ? substr($name, 0, 18) . '…' : $name)
        ->values();

    $sizes = $topProjects
        ->map(fn($project) => round(((int) ($project->size ?? 0)) / 1024 / 1024, 2))
        ->values();
@endphp

<div>
    <h6 class="text-muted mb-0" style="font-size:.8rem;">
        <i class="fas fa-hard-drive me-1"></i>Largest Projects
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
                orientation: 'h',
                y: @json($names),
                x: @json($sizes),
                marker: {
                    color: '#20c997'
                },
                hovertemplate: '%{y}: %{x} MB<extra></extra>'
            }], {
                margin: { t: 10, b: 35, l: 120, r: 10 },
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: { family: 'inherit', size: 11 },
                showlegend: false,
                xaxis: {
                    title: {
                        text: 'Size MB',
                        font: { size: 10 }
                    }
                },
                yaxis: {
                    autorange: 'reversed',
                    tickfont: { size: 9 }
                }
            }, {
                responsive: true,
                displayModeBar: false
            });
        })();
    </script>
@endpush
