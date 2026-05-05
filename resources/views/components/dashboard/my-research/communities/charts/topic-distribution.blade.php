@props([
    'topics' => collect(),
])

@php
    $topics = collect($topics)->take(10)->values();
    $chartId = 'my-research-community-topic-distribution-chart';

    $labels = $topics->pluck('tag')->values();
    $counts = $topics->pluck('count')->values();
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-0">
            <i class="fas fa-tags me-1"></i>Community Topic Distribution
        </h6>
        <p class="text-muted mb-1" style="font-size:.7rem;">
            Most common tags across community datasets.
        </p>

        @if($topics->isEmpty())
            <p class="text-muted mb-0">No topic chart data available.</p>
        @else
            <div id="{{ $chartId }}" style="height:280px;"></div>

            @push('scripts')
                <script>
                    (function () {
                        const chart = document.getElementById(@json($chartId));
                        if (!chart || !window.Plotly) {
                            return;
                        }

                        Plotly.newPlot(chart, [{
                            type: 'pie',
                            labels: @json($labels),
                            values: @json($counts),
                            hole: .55,
                            textinfo: 'label+percent',
                            hovertemplate: '%{label}: %{value} dataset tag occurrence(s)<extra></extra>',
                            marker: {
                                colors: [
                                    '#0d6efd',
                                    '#198754',
                                    '#dc3545',
                                    '#fd7e14',
                                    '#6f42c1',
                                    '#0dcaf0',
                                    '#20c997',
                                    '#d63384',
                                    '#ffc107',
                                    '#6c757d',
                                ],
                            },
                        }], {
                            paper_bgcolor: 'transparent',
                            plot_bgcolor: 'transparent',
                            font: {family: 'inherit', size: 10},
                            showlegend: false,
                            margin: {t: 5, b: 5, l: 5, r: 5},
                        }, {
                            responsive: true,
                            displayModeBar: false,
                        });
                    })();
                </script>
            @endpush
        @endif
    </div>
</div>
