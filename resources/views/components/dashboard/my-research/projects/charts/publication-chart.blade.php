@props([
    'projects' => collect(),
])

@php
    $chartId = 'my-research-project-publication-chart';

    $projects = collect($projects);
    $withPublished = $projects->filter(fn($project) => (int) ($project->published_datasets_count ?? 0) > 0)->count();
    $withUnpublished = $projects->filter(fn($project) => (int) ($project->unpublished_datasets_count ?? 0) > 0)->count();
    $withoutDatasets = $projects->filter(function ($project) {
        return (int) ($project->published_datasets_count ?? 0) === 0
            && (int) ($project->unpublished_datasets_count ?? 0) === 0;
    })->count();
@endphp

<div>
    <h6 class="text-muted mb-0" style="font-size:.8rem;">
        <i class="fas fa-database me-1"></i>Dataset Publication Status
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
                x: ['Published', 'Unpublished', 'No datasets'],
                y: [
                    {{ $withPublished }},
                    {{ $withUnpublished }},
                    {{ $withoutDatasets }}
                ],
                marker: {
                    color: ['#198754', '#ffc107', '#ced4da']
                },
                hovertemplate: '%{x}: %{y} project(s)<extra></extra>'
            }], {
                margin: { t: 10, b: 35, l: 35, r: 10 },
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: { family: 'inherit', size: 11 },
                showlegend: false,
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
