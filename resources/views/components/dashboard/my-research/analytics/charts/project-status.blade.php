@props([
    'activeProjects' => collect(),
    'recentlyAccessedProjects' => collect(),
    'archivedProjects' => collect(),
    'deletedProjects' => collect(),
])

@php
    $chartId = 'my-research-project-status-chart';

    $labels = ['Active', 'Recently Accessed', 'Archived', 'Deleted'];
    $values = [
        collect($activeProjects)->count(),
        collect($recentlyAccessedProjects)->count(),
        collect($archivedProjects)->count(),
        collect($deletedProjects)->count(),
    ];

    $hasData = collect($values)->sum() > 0;
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-0">
            <i class="fas fa-folder-open me-1"></i>Project Status
        </h6>
        <p class="text-muted mb-2" style="font-size:.7rem;">
            Project counts by dashboard status.
        </p>

        @if($hasData)
            <div id="{{ $chartId }}" style="height:260px;"></div>
        @else
            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                 style="height:260px;">
                No project status data
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
                        hole: 0.55,
                        marker: {
                            colors: ['#0d6efd', '#6f42c1', '#6c757d', '#dc3545']
                        },
                        textinfo: 'label+value',
                        hoverinfo: 'label+value+percent'
                    }], {
                        paper_bgcolor: 'transparent',
                        plot_bgcolor: 'transparent',
                        font: {family: 'inherit', size: 11},
                        showlegend: true,
                        legend: {orientation: 'h', x: 0.5, xanchor: 'center', y: -0.15},
                        margin: {t: 10, b: 45, l: 10, r: 10}
                    }, {
                        responsive: true,
                        displayModeBar: false
                    });
                });
            })();
        </script>
    @endpush
@endif
