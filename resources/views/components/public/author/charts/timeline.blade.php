@props([
    'pubTimeline' => [],
])

@if(count($pubTimeline) > 1)
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-calendar-alt me-1"></i> Publication Timeline
                </h6>

                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Owned datasets published per month
                </p>

                <div id="chart-author-timeline" style="height:200px;"></div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        (function () {
            const plotConfig = {responsive: true, displayModeBar: false};
            const base = (extra) => Object.assign({
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: {family: 'inherit', size: 11},
                showlegend: false,
            }, extra);

            @if(count($pubTimeline) > 1)
            Plotly.newPlot('chart-author-timeline', [{
                type: 'bar',
                x: @json(array_keys($pubTimeline)),
                y: @json(array_values($pubTimeline)),
                marker: {color: '#0d6efd'},
                hovertemplate: '%{x}: %{y} dataset(s)<extra></extra>',
            }], base({
                margin: {t: 10, b: 55, l: 35, r: 10},
                xaxis: {tickangle: -45, tickfont: {size: 9}},
                yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
            }), plotConfig);
            @endif
        })();
    </script>
@endpush
