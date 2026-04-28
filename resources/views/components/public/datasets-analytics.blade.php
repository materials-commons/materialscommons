{{-- ══ KPI strip ════════════════════════════════════════════════════════════════ --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-sm-4">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Datasets</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($totalDatasets) }}</div>
            <div class="text-muted" style="font-size:.65rem;">published</div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Total Views</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($totalViews) }}</div>
            <div class="text-muted" style="font-size:.65rem;">across all datasets</div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Total Downloads</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($totalDownloads) }}</div>
            <div class="text-muted" style="font-size:.65rem;">across all datasets</div>
        </div>
    </div>
</div>

{{-- ══ Analytics — collapsible, default CLOSED ════════════════════════════════ --}}
@if($totalDatasets > 0)
    <div class="d-flex align-items-center mb-2">
        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                type="button"
                id="pub-analytics-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#pub-analytics"
                aria-expanded="false"
                aria-controls="pub-analytics">
            <i class="fas fa-chevron-right fa-fw" id="pub-analytics-chevron"
               style="transition:transform .2s; font-size:.75rem;"></i>
            <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                Analytics
            </span>
        </button>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>

    <div class="collapse mb-4" id="pub-analytics">
        <div class="row g-3">

            {{-- Publications timeline --}}
            @if(count($pubMonthLabels) > 1)
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-calendar-alt me-1"></i> Publication Timeline
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Datasets published per month
                            </p>
                            <div id="chart-pub-timeline" style="height:200px;"></div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Top by views --}}
            @if(count($topViewsNames) > 0)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-eye me-1"></i> Most Viewed
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Top {{ count($topViewsNames) }} datasets by views
                            </p>
                            <div id="chart-pub-views"
                                 style="height:{{ min(60 + count($topViewsNames) * 30, 360) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Top by downloads --}}
            @if(count($topDownloadsNames) > 0)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-download me-1"></i> Most Downloaded
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Top {{ count($topDownloadsNames) }} datasets by downloads
                            </p>
                            <div id="chart-pub-downloads"
                                 style="height:{{ min(60 + count($topDownloadsNames) * 30, 360) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- License distribution --}}
            @if(count($licenseLabels) > 0)
                <div class="col-12 col-md-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-balance-scale me-1"></i> License Distribution
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                How datasets are licensed
                            </p>
                            <div id="chart-pub-licenses"
                                 style="height:{{ min(60 + count($licenseLabels) * 30, 300) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const STORAGE_KEY = '{{ $storageKey }}';
                const panel   = document.getElementById('pub-analytics');
                const toggle  = document.getElementById('pub-analytics-toggle');
                const chevron = document.getElementById('pub-analytics-chevron');

                if (!panel) return;

                if (localStorage.getItem(STORAGE_KEY) === 'true') {
                    panel.classList.add('show');
                    if (chevron) chevron.style.transform = 'rotate(90deg)';
                    if (toggle)  toggle.setAttribute('aria-expanded', 'true');
                }
                panel.addEventListener('show.bs.collapse', () => {
                    if (chevron) chevron.style.transform = 'rotate(90deg)';
                    localStorage.setItem(STORAGE_KEY, 'true');
                });
                panel.addEventListener('hide.bs.collapse', () => {
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                    localStorage.setItem(STORAGE_KEY, 'false');
                });
                panel.addEventListener('shown.bs.collapse', () => {
                    panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                });

                const plotConfig = {responsive: true, displayModeBar: false};
                const base = (extra) => Object.assign({
                    paper_bgcolor: 'transparent', plot_bgcolor: 'transparent',
                    font: {family: 'inherit', size: 11}, showlegend: false,
                }, extra);

                @if(count($pubMonthLabels) > 1)
                Plotly.newPlot('chart-pub-timeline', [{
                    type: 'bar',
                    x:    @json($pubMonthLabels),
                    y:    @json($pubMonthValues),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{x}: %{y} dataset(s)<extra></extra>',
                }], base({
                    margin: {t: 10, b: 55, l: 40, r: 10},
                    xaxis: {tickangle: -45, tickfont: {size: 9}},
                    yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                }), plotConfig);
                @endif

                @if(count($topViewsNames) > 0)
                Plotly.newPlot('chart-pub-views', [{
                    type: 'bar', orientation: 'h',
                    y:    @json($topViewsNames),
                    x:    @json($topViewsValues),
                    marker: {color: '#198754'},
                    hovertemplate: '%{y}: %{x:,} views<extra></extra>',
                    text: @json(array_map(fn($v) => number_format($v), $topViewsValues)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 200, r: 20},
                    xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                            title: {text: 'views', font: {size: 10}}},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                @if(count($topDownloadsNames) > 0)
                Plotly.newPlot('chart-pub-downloads', [{
                    type: 'bar', orientation: 'h',
                    y:    @json($topDownloadsNames),
                    x:    @json($topDownloadsValues),
                    marker: {color: '#0dcaf0'},
                    hovertemplate: '%{y}: %{x:,} downloads<extra></extra>',
                    text: @json(array_map(fn($v) => number_format($v), $topDownloadsValues)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 200, r: 20},
                    xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                            title: {text: 'downloads', font: {size: 10}}},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                @if(count($licenseLabels) > 0)
                Plotly.newPlot('chart-pub-licenses', [{
                    type: 'bar', orientation: 'h',
                    y:    @json($licenseLabels),
                    x:    @json($licenseValues),
                    marker: {color: '#6f42c1'},
                    hovertemplate: '%{y}: %{x} dataset(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $licenseValues)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 200, r: 20},
                    xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                            title: {text: 'datasets', font: {size: 10}}},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

            })();
        </script>
    @endpush
@endif
