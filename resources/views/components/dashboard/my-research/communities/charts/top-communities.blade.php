@props([
    'communities' => collect(),
])

@php
    $communities = collect($communities)->take(10)->values();
    $chartId = 'my-research-top-communities-chart';

    $names = $communities
        ->pluck('name')
        ->map(fn($name) => mb_strlen($name) > 36 ? mb_substr($name, 0, 34) . '…' : $name)
        ->values();

    $datasetCounts = $communities->pluck('dataset_count')->values();
    $views = $communities->pluck('views_count')->values();
    $downloads = $communities->pluck('downloads_count')->values();
    $urls = $communities->pluck('url')->values();
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-0">
            <i class="fas fa-chart-bar me-1"></i>Top Communities
        </h6>
        <p class="text-muted mb-1" style="font-size:.7rem;">
            Ranked by published datasets — click a bar to open the public community page.
        </p>

        @if($communities->isEmpty())
            <p class="text-muted mb-0">No community chart data available.</p>
        @else
            <div id="{{ $chartId }}"
                 style="height:{{ min(90 + $communities->count() * 30, 440) }}px; cursor:pointer;"></div>

            @push('scripts')
                <script>
                    (function () {
                        const chart = document.getElementById(@json($chartId));
                        if (!chart || !window.Plotly) {
                            return;
                        }

                        const urls = @json($urls);
                        const views = @json($views);
                        const downloads = @json($downloads);

                        Plotly.newPlot(chart, [{
                            type: 'bar',
                            orientation: 'h',
                            y: @json($names),
                            x: @json($datasetCounts),
                            customdata: views.map((viewCount, index) => [viewCount, downloads[index]]),
                            marker: {color: '#0d6efd'},
                            hovertemplate: '%{y}<br>%{x} dataset(s)<br>%{customdata[0]} views<br>%{customdata[1]} downloads<extra></extra>',
                            text: @json($datasetCounts->map(fn($count) => (string) $count)->values()),
                            textposition: 'inside',
                            insidetextanchor: 'end',
                            textfont: {color: 'white', size: 9},
                        }], {
                            paper_bgcolor: 'transparent',
                            plot_bgcolor: 'transparent',
                            font: {family: 'inherit', size: 11},
                            showlegend: false,
                            margin: {t: 5, b: 35, l: 200, r: 20},
                            xaxis: {
                                tickformat: ',d',
                                tickfont: {size: 9},
                                gridcolor: '#dee2e6',
                                title: {text: 'datasets', font: {size: 10}},
                            },
                            yaxis: {
                                autorange: 'reversed',
                                tickfont: {size: 10},
                            },
                        }, {
                            responsive: true,
                            displayModeBar: false,
                        });

                        chart.on('plotly_click', function (data) {
                            window.location.href = urls[data.points[0].pointIndex];
                        });
                    })();
                </script>
            @endpush
        @endif
    </div>
</div>
