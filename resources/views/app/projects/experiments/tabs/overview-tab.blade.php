@php
    $hasProcessChart = isset($activitiesGroup) && count($activitiesGroup) > 0;
    $hasFileChart    = isset($fileDescriptionTypes) && count($fileDescriptionTypes) > 0;
    $hasAnyChart     = $hasProcessChart || $hasFileChart;
@endphp

<div>
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
        <div>
            <h2 class="h5 mb-1">Study Overview</h2>
            <div class="text-muted small">
                High-level counts, storage, import source, and study metadata.
            </div>
        </div>

        <span class="badge text-bg-light border">
            <i class="fas fa-flask me-1"></i>
            Current Study
        </span>
    </div>

    {{-- ── KPI strip ────────────────────────────────────────────────────────── --}}
    <x-projects.kpi-strip
        :samples="$experiment->experimental_entities_count"
        :computations="$experiment->computational_entities_count"
        :processes="$experiment->activities_count"
        :workflows="$experiment->workflows_count"
        :attribute-count="$entityAttributesCount + $activityAttributesCount"
        :attribute-count-hint="number_format($entityAttributesCount).' sample · '.number_format($activityAttributesCount).' proc'"
        :total-size="formatBytes($totalFilesSize)"
        :total-size-formatted="true"
    />

    {{-- ── Study details and import source ─────────────────────────────────── --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-7">
            <div class="card h-100">
                <div class="card-header">
                    <div>
                        <h3 class="h6 mb-1">
                            <i class="fas fa-info-circle me-1"></i>
                            Study Details
                        </h3>
                        <div class="text-muted small">Name, summary, description, ownership, and timestamps.</div>
                    </div>
                </div>

                <div class="card-body">
                    <x-show-standard-details :item="$experiment"/>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card h-100">
                <div class="card-header">
                    <div>
                        <h3 class="h6 mb-1">
                            <i class="fas fa-file-import me-1"></i>
                            Current Import Source
                        </h3>
                        <div class="text-muted small">Where this study was last loaded from.</div>
                    </div>

                    @if(!is_null($experiment->sheet) || !is_null($experiment->loaded_file_path))
                        <span class="badge text-bg-success">Loaded</span>
                    @else
                        <span class="badge text-bg-secondary">Manual</span>
                    @endif
                </div>

                <div class="card-body">
                    @if(!is_null($experiment->sheet))
                        <dl class="row mb-0 small">
                            <dt class="col-sm-4 text-muted">Source</dt>
                            <dd class="col-sm-8">
                                <i class="fab fa-google-drive text-success me-1"></i>
                                Google Sheet
                            </dd>

                            <dt class="col-sm-4 text-muted">Sheet</dt>
                            <dd class="col-sm-8">
                                <a href="{{ $experiment->sheet->url }}"
                                   target="_blank"
                                   class="text-decoration-none">
                                    {{ $experiment->sheet->title }}
                                </a>
                            </dd>

                            <dt class="col-sm-4 text-muted">Status</dt>
                            <dd class="col-sm-8 mb-0">
                                <span class="badge text-bg-success">Loaded</span>
                            </dd>
                        </dl>
                    @elseif(!is_null($experiment->loaded_file_path))
                        <dl class="row mb-0 small">
                            <dt class="col-sm-4 text-muted">Source</dt>
                            <dd class="col-sm-8">
                                <i class="fas fa-file-excel text-success me-1"></i>
                                Project spreadsheet
                            </dd>

                            <dt class="col-sm-4 text-muted">File</dt>
                            <dd class="col-sm-8">
                                <a href="{{ route('projects.files.by-path', [$project, 'path' => $experiment->loaded_file_path]) }}"
                                   class="text-decoration-none">
                                    {{ $experiment->loaded_file_path }}
                                </a>
                            </dd>

                            <dt class="col-sm-4 text-muted">Status</dt>
                            <dd class="col-sm-8 mb-0">
                                <span class="badge text-bg-success">Loaded</span>
                            </dd>
                        </dl>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-file-import fa-2x mb-2"></i>
                            <div class="fw-semibold">No import source recorded</div>
                            <div style="font-size:.85rem;">
                                This study does not have a spreadsheet or Google Sheet source recorded.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div>
                <h3 class="h6 mb-1">
                    <i class="fas fa-align-left me-1"></i>
                    Overview
                </h3>
                <div class="text-muted small">Study overview content and description.</div>
            </div>
        </div>

        <div class="card-body">
            @include('partials.overview._overview')
        </div>
    </div>

    @if($hasAnyChart)
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <h2 class="h5 mb-1">Analytics</h2>
                <div class="text-muted small">
                    Process type and file type distributions for this study.
                </div>
            </div>

            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2"
                    type="button"
                    id="exp-analytics-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#exp-analytics"
                    aria-expanded="false"
                    aria-controls="exp-analytics">
                <i class="fas fa-chevron-right fa-fw"
                   id="exp-analytics-chevron"
                   style="transition: transform 0.2s; font-size:.75rem;"></i>
                <span>Show Analytics</span>
            </button>
        </div>

        <div class="collapse mb-4" id="exp-analytics">
            <div class="row g-4">
                @if($hasProcessChart)
                    <div class="col-12 {{ $hasFileChart ? 'col-xl-7' : '' }}">
                        <div class="card h-100">
                            <div class="card-header">
                                <div>
                                    <h3 class="h6 mb-1">
                                        <i class="fas fa-cogs me-1"></i>
                                        Process Types
                                    </h3>
                                    <div class="text-muted small">
                                        Count of each process type used in this study.
                                    </div>
                                </div>

                                <span class="badge text-bg-success">
                                    {{ number_format(count($activitiesGroup)) }} types
                                </span>
                            </div>

                            <div class="card-body">
                                <div id="chart-exp-processes"
                                     style="height:{{ min(60 + count($activitiesGroup) * 28, 420) }}px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($hasFileChart)
                    <div class="col-12 {{ $hasProcessChart ? 'col-xl-5' : '' }}">
                        <div class="card h-100">
                            <div class="card-header">
                                <div>
                                    <h3 class="h6 mb-1">
                                        <i class="fas fa-file-alt me-1"></i>
                                        File Types
                                    </h3>
                                    <div class="text-muted small">
                                        Distribution of file types in this study.
                                    </div>
                                </div>

                                <span class="badge text-bg-info">
                                    {{ number_format(count($fileDescriptionTypes)) }} types
                                </span>
                            </div>

                            <div class="card-body">
                                <div id="chart-exp-filetypes" style="height:240px;"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@if($hasAnyChart)
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

                @if($hasProcessChart)
                @php
                    $procNames  = collect($activitiesGroup)->pluck('name')->map(fn($n) => strlen($n) > 30 ? substr($n, 0, 28).'…' : $n)->values()->toArray();
                    $procCounts = collect($activitiesGroup)->pluck('count')->values()->toArray();
                    array_multisort($procCounts, SORT_DESC, $procNames);
                @endphp
                Plotly.newPlot('chart-exp-processes', [{
                    type: 'bar',
                    orientation: 'h',
                    y: @json($procNames),
                    x: @json($procCounts),
                    marker: {color: '#198754'},
                    hovertemplate: '%{y}: %{x:,}<extra></extra>',
                    text: @json(array_map(fn($c) => number_format($c), $procCounts)),
                    textposition: 'inside',
                    insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 160, r: 20},
                    xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                @if($hasFileChart)
                @php
                    $fileLabels = array_keys($fileDescriptionTypes);
                    $fileValues = array_values($fileDescriptionTypes);
                @endphp
                Plotly.newPlot('chart-exp-filetypes', [{
                    type: 'pie',
                    hole: 0.5,
                    labels: @json($fileLabels),
                    values: @json($fileValues),
                    textinfo: 'label+value',
                    hoverinfo: 'label+value+percent',
                    textfont: {size: 10},
                }], base({
                    margin: {t: 10, b: 10, l: 10, r: 10},
                    showlegend: false,
                }), plotConfig);
                @endif

                const STORAGE_KEY = 'mc_exp_overview_analytics_open';
                const panel = document.getElementById('exp-analytics');
                const chevron = document.getElementById('exp-analytics-chevron');
                const toggle = document.getElementById('exp-analytics-toggle');

                if (panel && localStorage.getItem(STORAGE_KEY) === 'true') {
                    panel.classList.add('show');
                    chevron.style.transform = 'rotate(90deg)';
                    toggle.setAttribute('aria-expanded', 'true');
                }

                if (panel) {
                    panel.addEventListener('show.bs.collapse', () => {
                        chevron.style.transform = 'rotate(90deg)';
                        localStorage.setItem(STORAGE_KEY, 'true');
                    });

                    panel.addEventListener('hide.bs.collapse', () => {
                        chevron.style.transform = 'rotate(0deg)';
                        localStorage.setItem(STORAGE_KEY, 'false');
                    });

                    panel.addEventListener('shown.bs.collapse', () => {
                        panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                    });
                }
            })();
        </script>
    @endpush
@endif
