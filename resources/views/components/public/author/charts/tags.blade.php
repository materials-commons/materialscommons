@props([
    'tagNames' => [],
    'tagCounts' => [],
])

@if(count($tagNames) > 0)
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-tags me-1"></i> Top Tags
                </h6>

                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Across all datasets
                </p>

                <div id="chart-author-tags"
                     style="height:{{ min(60 + count($tagNames) * 26, 380) }}px;"></div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        (function() {
            @if(count($tagNames) > 0)
            Plotly.newPlot('chart-author-tags', [{
                type: 'bar',
                orientation: 'h',
                y: @json($tagNames),
                x: @json($tagCounts),
                marker: {color: '#198754'},
                hovertemplate: '%{y}: %{x} dataset(s)<extra></extra>',
                text: @json(array_map(fn($v) => (string) $v, $tagCounts)),
                textposition: 'inside',
                insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 160, r: 20},
                xaxis: {
                    tickformat: ',d',
                    tickfont: {size: 9},
                    gridcolor: '#dee2e6',
                    title: {text: 'datasets', font: {size: 10}},
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            @endif
        })();
    </script>
@endpush
