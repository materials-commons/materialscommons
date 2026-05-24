@props([
    'projects' => collect(),
])

@php
    $chartId = 'my-research-project-file-counts-chart';

    $projects = collect($projects)->values();

    $labels = $projects->map(fn ($project) => $project->name ?? 'Untitled Project')->values();
    $values = $projects->map(fn ($project) => max((int) ($project->file_count ?? 0), 1))->values();

    $maxValue = max($values->max() ?? 1, 1);
    $maxPower = (int) ceil(log10($maxValue));

    $tickValues = collect(range(0, $maxPower))
        ->map(fn ($power) => 10 ** $power)
        ->filter(fn ($value) => $value <= $maxValue * 1.05)
        ->values();

    if ($tickValues->last() < $maxValue) {
        $tickValues->push(10 ** $maxPower);
    }

    $tickText = $tickValues
        ->map(fn ($value) => number_format($value))
        ->values();

    $hasData = $values->sum() > 0;
    $height = min(110 + max($labels->count(), 1) * 36, 420);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-0">
            <i class="fas fa-file me-1"></i>Largest Projects by File Count
        </h6>
        <p class="text-muted mb-2" style="font-size:.7rem;">
            Top projects by stored aggregate file count, shown on a log scale.
        </p>

        @if($hasData)
            <div id="{{ $chartId }}" style="height:{{ $height }}px;"></div>
        @else
            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                 style="height:260px;">
                No project file count data
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
                        x: @json($values),
                        y: @json($labels),
                        type: 'bar',
                        orientation: 'h',
                        marker: {color: '#fd7e14'},
                        hovertemplate: '%{y}<br>%{x:,} files<extra></extra>'
                    }], {
                        paper_bgcolor: 'transparent',
                        plot_bgcolor: 'transparent',
                        font: {family: 'inherit', size: 11},
                        showlegend: false,
                        margin: {t: 5, b: 55, l: 180, r: 20},
                        xaxis: {
                            type: 'log',
                            title: {text: 'Files, log scale', font: {size: 10}},
                            tickmode: 'array',
                            tickvals: @json($tickValues),
                            ticktext: @json($tickText),
                            tickangle: 0,
                            tickfont: {size: 9},
                            gridcolor: '#dee2e6',
                            automargin: true
                        },
                        yaxis: {
                            autorange: 'reversed',
                            automargin: true,
                            tickfont: {size: 10}
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
