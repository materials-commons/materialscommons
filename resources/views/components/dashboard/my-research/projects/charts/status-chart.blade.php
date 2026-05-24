@props([
    'projects' => collect(),
    'activeProjects' => collect(),
    'archivedProjects' => collect(),
    'deletedProjects' => collect(),
])

@php
    $chartId = 'my-research-project-status-chart';
    $activeCount = collect($activeProjects)->count();
    $archivedCount = collect($archivedProjects)->count();
    $deletedCount = collect($deletedProjects)->count();
    $otherCount = max(collect($projects)->count() - $activeCount, 0);
@endphp

<div>
    <h6 class="text-muted mb-0" style="font-size:.8rem;">
        <i class="fas fa-folder-tree me-1"></i>Status Mix
    </h6>
    <div id="{{ $chartId }}" class="js-plotly-plot" style="height:190px;"></div>
</div>

@push('scripts')
    <script>
        (function () {
            const el = document.getElementById(@json($chartId));
            if (!el || typeof Plotly === 'undefined') return;

            Plotly.newPlot(el, [{
                type: 'pie',
                hole: 0.55,
                labels: ['Active', 'Other Current', 'Archived', 'Deleted'],
                values: [
                    {{ $activeCount }},
                    {{ $otherCount }},
                    {{ $archivedCount }},
                    {{ $deletedCount }}
                ],
                marker: {
                    colors: ['#0d6efd', '#6ea8fe', '#6c757d', '#dc3545']
                },
                textinfo: 'value',
                hoverinfo: 'label+value+percent'
            }], {
                margin: { t: 5, b: 35, l: 5, r: 5 },
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: { family: 'inherit', size: 11 },
                legend: {
                    orientation: 'h',
                    x: 0.5,
                    xanchor: 'center',
                    y: -0.1,
                    font: { size: 10 }
                }
            }, {
                responsive: true,
                displayModeBar: false
            });
        })();
    </script>
@endpush
