@props([
    'tags' => collect(),
])

@php
    $tags = collect($tags)->take(15)->values();
    $chartId = 'my-research-top-tags-chart';

    $tagNames = $tags
        ->pluck('tag')
        ->map(fn($tag) => mb_strlen($tag) > 32 ? mb_substr($tag, 0, 30) . '…' : $tag)
        ->values();

    $tagCounts = $tags->pluck('count')->values();
    $tagUrls = $tags
        ->pluck('tag')
        ->map(fn($tag) => route('public.tags.search', ['tag' => $tag]))
        ->values();
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-0">
            <i class="fas fa-chart-bar me-1"></i>Top Tags Across All Datasets
        </h6>
        <p class="text-muted mb-1" style="font-size:.7rem;">
            Counts combine your datasets and datasets where you are listed — click a bar to open the public tag page.
        </p>

        @if($tags->isEmpty())
            <p class="text-muted mb-0">No tags available for charting.</p>
        @else
            <div id="{{ $chartId }}"
                 style="height:{{ min(90 + $tags->count() * 28, 480) }}px; cursor:pointer;"></div>

            @push('scripts')
                <script>
                    (function () {
                        const chart = document.getElementById(@json($chartId));
                        if (!chart || !window.Plotly) {
                            return;
                        }

                        const tagUrls = @json($tagUrls);

                        Plotly.newPlot(chart, [{
                            type: 'bar',
                            orientation: 'h',
                            y: @json($tagNames),
                            x: @json($tagCounts),
                            marker: {color: '#198754'},
                            hovertemplate: '%{y}: %{x} dataset(s)<extra></extra>',
                            text: @json($tagCounts->map(fn($count) => (string) $count)->values()),
                            textposition: 'inside',
                            insidetextanchor: 'end',
                            textfont: {color: 'white', size: 9},
                        }], {
                            paper_bgcolor: 'transparent',
                            plot_bgcolor: 'transparent',
                            font: {family: 'inherit', size: 11},
                            showlegend: false,
                            margin: {t: 5, b: 35, l: 180, r: 20},
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
                            window.location.href = tagUrls[data.points[0].pointIndex];
                        });
                    })();
                </script>
            @endpush
        @endif
    </div>
</div>
