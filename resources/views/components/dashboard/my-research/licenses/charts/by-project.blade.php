{{-- resources/views/components/dashboard/my-research/licenses/charts/by-project.blade.php --}}
@props([
    'licensesByProject' => collect(),
])

@php
    $chartId = 'my-research-licenses-by-project-chart-' . uniqid();
    $licensesByProject = collect($licensesByProject);

    $projects = $licensesByProject->keys()->values();
    $licenses = $licensesByProject
        ->flatMap(fn($counts) => collect($counts)->keys())
        ->unique()
        ->values();

    $traces = $licenses->map(function ($license) use ($licensesByProject, $projects) {
        return [
            'name' => $license,
            'type' => 'bar',
            'x' => $projects,
            'y' => $projects->map(fn($project) => (int) (collect($licensesByProject->get($project))->get($license, 0)))->values(),
        ];
    })->values();

    $hasData = $traces->flatMap(fn($trace) => $trace['y'])->sum() > 0;
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-1">
            <i class="fas fa-folder-open me-1"></i>Licenses by Project
        </h6>
        <p class="text-muted mb-2" style="font-size:.8rem;">
            Licenses used by datasets in each project.
        </p>

        @if($hasData)
            <div id="{{ $chartId }}" class="js-plotly-plot" style="height:280px;"></div>
        @else
            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                 style="height:280px;">
                No project license data
            </div>
        @endif
    </div>
</div>

@if($hasData)
    @push('scripts')
        <script>
            (function () {
                const render = function () {
                    const div = document.getElementById(@json($chartId));

                    if (!div || !window.Plotly || div.dataset.rendered === 'true') {
                        return;
                    }

                    div.dataset.rendered = 'true';

                    Plotly.newPlot(div, @json($traces), {
                        barmode: 'stack',
                        paper_bgcolor: 'transparent',
                        plot_bgcolor: 'transparent',
                        font: {family: 'inherit', size: 11},
                        margin: {t: 10, b: 90, l: 35, r: 10},
                        xaxis: {automargin: true},
                        yaxis: {title: 'Datasets', rangemode: 'tozero'},
                        legend: {orientation: 'h', x: 0.5, xanchor: 'center', y: -0.28}
                    }, {
                        responsive: true,
                        displayModeBar: false
                    });
                };

                document.addEventListener('DOMContentLoaded', render);
                document.getElementById('my-research-licenses-tab')?.addEventListener('shown.bs.tab', render);
            })();
        </script>
    @endpush
@endif
