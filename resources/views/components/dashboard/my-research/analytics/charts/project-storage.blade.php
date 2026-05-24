@props([
    'projects' => collect(),
])

@php
    $chartId = 'my-research-project-storage-chart';

    $projects = collect($projects)->values();

    $labels = $projects->map(fn ($project) => $project->name ?? 'Untitled Project')->values();

    $storageValues = $projects
        ->map(function ($project) {
            $sizeInBytes = (int) ($project->size ?? 0);

            return $sizeInBytes / 1024 / 1024 / 1024;
        })
        ->values();

    $values = $storageValues
        ->map(fn ($value) => max(round($value, 2), 0.01))
        ->values();

    $formattedValues = $storageValues
        ->map(function ($value) {
            if ($value > 0 && $value < 0.01) {
                return '<0.01 GB';
            }

            if ($value < 1) {
                return number_format($value, 2) . ' GB';
            }

            return number_format($value, 2) . ' GB';
        })
        ->values();

    $customData = $formattedValues
        ->map(fn ($value) => [$value])
        ->values();

    $maxValue = max($values->max() ?? 0.01, 0.01);
    $maxPower = (int) ceil(log10($maxValue));
    $minPower = -2;

    $tickValues = collect(range($minPower, $maxPower))
        ->map(fn ($power) => 10 ** $power)
        ->filter(fn ($value) => $value <= $maxValue * 1.05)
        ->values();

    if ($tickValues->isEmpty()) {
        $tickValues = collect([0.01]);
    }

    if ($tickValues->last() < $maxValue) {
        $tickValues->push(10 ** $maxPower);
    }

    $tickText = $tickValues
        ->map(function ($value) {
            if ($value < 1) {
                return rtrim(rtrim(number_format($value, 2), '0'), '.');
            }

            return number_format($value);
        })
        ->values();

    $hasData = $values->sum() > 0;
    $height = min(110 + max($labels->count(), 1) * 36, 420);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-0">
            <i class="fas fa-hdd me-1"></i>Largest Projects by Storage
        </h6>
        <p class="text-muted mb-2" style="font-size:.7rem;">
            Top projects by stored aggregate size, shown in GB on a log scale.
        </p>

        @if($hasData)
            <div id="{{ $chartId }}" style="height:{{ $height }}px;"></div>
        @else
            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                 style="height:260px;">
                No project storage data
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

                    Plotly.purge(div);

                    Plotly.newPlot(div, [{
                        x: @json($values),
                        y: @json($labels),
                        customdata: @json($customData),
                        type: 'bar',
                        orientation: 'h',
                        marker: {color: '#6610f2'},
                        text: @json($formattedValues),
                        textposition: 'inside',
                        insidetextanchor: 'end',
                        textfont: {color: 'white', size: 9},
                        hovertemplate: '%{y}<br>%{customdata[0]}<extra></extra>'
                    }], {
                        paper_bgcolor: 'transparent',
                        plot_bgcolor: 'transparent',
                        font: {family: 'inherit', size: 11},
                        showlegend: false,
                        margin: {t: 5, b: 55, l: 180, r: 20},
                        xaxis: {
                            type: 'log',
                            title: {text: 'GB, log scale', font: {size: 10}},
                            tickmode: 'array',
                            tickvals: @json($tickValues),
                            ticktext: @json($tickText),
                            ticksuffix: ' GB',
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
