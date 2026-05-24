@php
    $hasFileData     = !empty($fileDescriptionTypes);
    $hasActivityData = ($activitiesGroup instanceof \Illuminate\Support\Collection)
        ? $activitiesGroup->isNotEmpty()
        : !empty($activitiesGroup);

    if ($hasFileData) {
        $ftNames  = array_keys($fileDescriptionTypes);
        $ftCounts = array_values($fileDescriptionTypes);
    }

    if ($hasActivityData) {
        $actNames  = ($activitiesGroup instanceof \Illuminate\Support\Collection)
            ? $activitiesGroup->pluck('name')->toArray()
            : array_column((array)$activitiesGroup, 'name');
        $actCounts = ($activitiesGroup instanceof \Illuminate\Support\Collection)
            ? $activitiesGroup->pluck('count')->toArray()
            : array_column((array)$activitiesGroup, 'count');
    }

    // ── Dataset Composition donut ────────────────────────────────────────────────
    $compRaw = [
        'Files'       => $dataset->files_count       ?? 0,
        'Samples'     => $dataset->entities_count    ?? 0,
        'Activities'  => $dataset->activities_count  ?? 0,
        'Workflows'   => $dataset->workflows_count   ?? 0,
        'Experiments' => $dataset->experiments_count ?? 0,
    ];
    $compFiltered = array_filter($compRaw, fn($v) => $v > 0);
    $compLabels   = array_keys($compFiltered);
    $compValues   = array_values($compFiltered);
    $hasCompData  = count($compLabels) > 0;

    // ── Views & Downloads timeline ───────────────────────────────────────────────
    $viewsByMonth     = $dataset->views()
        ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as m, count(*) as n")
        ->groupBy('m')->orderBy('m')->pluck('n', 'm');
    $downloadsByMonth = $dataset->downloads()
        ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as m, count(*) as n")
        ->groupBy('m')->orderBy('m')->pluck('n', 'm');
    $engagementMonths = $viewsByMonth->keys()->merge($downloadsByMonth->keys())
        ->unique()->sort()->values();
    $engagementMonthLabels    = $engagementMonths->toArray();
    $engagementViewValues     = $engagementMonths->map(fn($m) => (int)$viewsByMonth->get($m, 0))->values()->toArray();
    $engagementDownloadValues = $engagementMonths->map(fn($m) => (int)$downloadsByMonth->get($m, 0))->values()->toArray();
    $hasEngagementData        = count($engagementMonthLabels) > 0;
@endphp

{{-- ══ Dataset KPI strip — always visible ══════════════════════════════════════ --}}
<div class="row g-2 mb-4">
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Files</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($dataset->files_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">{{ formatBytes($dataset->total_files_size) }}</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Samples</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($dataset->entities_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">in dataset</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Workflows</div>
            <div class="fw-bold fs-5 text-secondary">{{ number_format($dataset->workflows_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">documented</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Views</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($dataset->views_count ?? 0) }}</div>
            <div class="text-muted" style="font-size:.65rem;">total views</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Downloads</div>
            <div class="fw-bold fs-5 text-warning">{{ number_format($dataset->downloads_count ?? 0) }}</div>
            <div class="text-muted" style="font-size:.65rem;">total downloads</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Published</div>
            <div class="fw-bold text-muted" style="font-size:.8rem;">
                {{ $dataset->published_at ? $dataset->published_at->format('M j, Y') : '—' }}
            </div>
            <div class="text-muted" style="font-size:.65rem;">
                {{ $dataset->published_at ? $dataset->published_at->diffForHumans() : 'not published' }}
            </div>
        </div>
    </div>
</div>

{{-- ══ Analytics — collapsible ═════════════════════════════════════════════════ --}}
@if($hasFileData || $hasActivityData || $hasCompData || $hasEngagementData)
    <div class="d-flex align-items-center mb-2">
        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                type="button"
                id="ds-analytics-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#ds-analytics"
                aria-expanded="false"
                aria-controls="ds-analytics">
            <i class="fas fa-chevron-right fa-fw" id="ds-analytics-chevron"
               style="transition:transform .2s; font-size:.75rem;"></i>
            <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                Analytics
            </span>
        </button>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>

    <div class="collapse mb-4" id="ds-analytics">
        <div class="row g-3">
            @if($hasFileData)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-file me-1"></i> File Types
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                {{ array_sum($ftCounts) }} files across {{ count($ftNames) }} type(s)
                            </p>
                            <div id="chart-ds-filetypes"
                                 style="height:{{ min(60 + count($ftNames) * 28, 360) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif
            @if($hasActivityData)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-cogs me-1"></i> Processes &amp; Activities
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Activity types documented in this dataset
                            </p>
                            <div id="chart-ds-activities"
                                 style="height:{{ min(60 + count($actNames) * 28, 360) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Row 2: Composition + Engagement timeline --}}
        @if($hasCompData || $hasEngagementData)
            <div class="row g-3 mt-0">

                {{-- Dataset Composition donut --}}
                @if($hasCompData)
                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 background-white">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-layer-group me-1"></i> Composition
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">
                                    Structural breakdown of dataset contents
                                </p>
                                <div id="chart-ds-composition" style="height:200px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Views & Downloads timeline --}}
                @if($hasEngagementData)
                    <div class="col-12 {{ $hasCompData ? 'col-md-8' : '' }}">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 background-white">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-chart-bar me-1"></i> Engagement Over Time
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">
                                    Unique views and downloads per month
                                </p>
                                <div id="chart-ds-engagement" style="height:200px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            (function () {
                const STORAGE_KEY = 'mc_ds_analytics_{{ $dataset->id }}';
                const panel = document.getElementById('ds-analytics');
                const chevron = document.getElementById('ds-analytics-chevron');
                const toggle = document.getElementById('ds-analytics-toggle');

                if (!panel) return;

                if (localStorage.getItem(STORAGE_KEY) === 'true') {
                    panel.classList.add('show');
                    if (chevron) chevron.style.transform = 'rotate(90deg)';
                    if (toggle) toggle.setAttribute('aria-expanded', 'true');
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

                @if($hasFileData)
                Plotly.newPlot('chart-ds-filetypes', [{
                    type: 'bar', orientation: 'h',
                    y: @json($ftNames),
                    x: @json($ftCounts),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{y}: %{x} file(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $ftCounts)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 160, r: 20},
                    xaxis: {
                        type: 'log',
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'files', font: {size: 10}}
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                @if($hasActivityData)
                Plotly.newPlot('chart-ds-activities', [{
                    type: 'bar', orientation: 'h',
                    y: @json($actNames),
                    x: @json($actCounts),
                    marker: {color: '#6f42c1'},
                    hovertemplate: '%{y}: %{x}<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $actCounts)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 160, r: 20},
                    xaxis: {
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'count', font: {size: 10}}
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                // ── Dataset Composition donut ─────────────────────────────────────
                @if($hasCompData)
                Plotly.newPlot('chart-ds-composition', [{
                    type: 'pie',
                    hole: 0.55,
                    labels:    @json($compLabels),
                    values:    @json($compValues),
                    marker: {colors: ['#0d6efd', '#0dcaf0', '#6f42c1', '#198754', '#ffc107']},
                    textinfo: 'value',
                    hoverinfo: 'label+value+percent',
                    domain: {x: [0, 1], y: [0, 1]},
                }], base({
                    showlegend: true,
                    legend: {orientation: 'h', x: 0.5, xanchor: 'center', y: -0.15, font: {size: 10}},
                    margin: {t: 10, b: 45, l: 5, r: 5},
                }), plotConfig);
                @endif

                // ── Views & Downloads timeline ────────────────────────────────────
                // Use a JS labels array + pointIndex to avoid Plotly date auto-detection
                @if($hasEngagementData)
                const engagementLabels = @json($engagementMonthLabels);
                Plotly.newPlot('chart-ds-engagement', [
                    {
                        type: 'bar',
                        name: 'Views',
                        x: engagementLabels,
                        y: @json($engagementViewValues),
                        marker: {color: '#0d6efd'},
                        hovertemplate: '%{x}: %{y} view(s)<extra></extra>',
                    },
                    {
                        type: 'bar',
                        name: 'Downloads',
                        x: engagementLabels,
                        y: @json($engagementDownloadValues),
                        marker: {color: '#198754'},
                        hovertemplate: '%{x}: %{y} download(s)<extra></extra>',
                    },
                ], base({
                    barmode: 'group',
                    showlegend: true,
                    legend: {orientation: 'h', x: 0.5, xanchor: 'center', y: -0.22, font: {size: 10}},
                    margin: {t: 10, b: 50, l: 30, r: 10},
                    xaxis: {tickangle: -35, tickfont: {size: 9}},
                    yaxis: {tickformat: 'd', dtick: 1, tickfont: {size: 9}},
                }), plotConfig);
                @endif
            })();
        </script>
    @endpush
@endif

{{-- ══ Dataset metadata ═════════════════════════════════════════════════════════ --}}
<form>
    <x-datasets.show-overview :dataset="$dataset">
        <div class="vr"></div>
        <div class="px-3 py-2">
            <div class="text-muted fw-semibold"
                 style="font-size:.7rem; text-transform:uppercase; letter-spacing:.04em;">Total Size
            </div>
            <div>{{ formatBytes($dataset->total_files_size) }}</div>
        </div>
    </x-datasets.show-overview>

    <x-datasets.show-authors :authors="$dataset->ds_authors" :author-users="$authorUsers"/>

    <div class="mb-3 mt-2">
        @include('partials.overview._overview')
    </div>

    @if(!blank($dataset->description))
        <x-show-description :description="$dataset->description"/>
    @elseif (!blank($dataset->summary))
        <x-show-summary :summary="$dataset->summary"/>
    @endif

    <x-datasets.show-tags :tags="$dataset->tags"/>

    <x-datasets.show-citations :dataset="$dataset"/>

    <x-display-markdown-file :file="$readme"></x-display-markdown-file>

    <x-datasets.show-papers-list :papers="$dataset->papers"/>


    <x-datasets.show-funding :dataset="$dataset"/>

    <x-datasets.show-overview-files :dataset="$dataset"/>

</form>
