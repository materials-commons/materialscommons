{{-- ── KPI strip ────────────────────────────────────────────────────────────── --}}
<div class="row g-2 mb-3 justify-content-center">
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm text-center py-2 background-white">
            <div class="text-muted small">Samples</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($experiment->experimental_entities_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">experimental</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm text-center py-2 background-white">
            <div class="text-muted small">Computations</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($experiment->computational_entities_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">computational</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm text-center py-2 background-white">
            <div class="text-muted small">Processes</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($experiment->activities_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">activities</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm text-center py-2 background-white">
            <div class="text-muted small">Workflows</div>
            <div class="fw-bold fs-5 text-secondary">{{ number_format($experiment->workflows_count) }}</div>
            <div class="text-muted" style="font-size:.65rem;">defined</div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm text-center py-2 background-white">
            <div class="text-muted small">Attributes</div>
            <div
                class="fw-bold fs-5 text-warning">{{ number_format($entityAttributesCount + $activityAttributesCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">
                {{ number_format($entityAttributesCount) }} sample · {{ number_format($activityAttributesCount) }} proc
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card border-0 shadow-sm text-center py-2 background-white">
            <div class="text-muted small">Total Size</div>
            <div class="fw-bold fs-5 text-muted">{{ formatBytes($totalFilesSize) }}</div>
            <div class="text-muted" style="font-size:.65rem;">file storage</div>
        </div>
    </div>
</div>

@php
    $hasProcessChart = isset($activitiesGroup) && count($activitiesGroup) > 0;
    $hasFileChart    = isset($fileDescriptionTypes) && count($fileDescriptionTypes) > 0;
    $hasAnyChart     = $hasProcessChart || $hasFileChart;
@endphp

@if($hasAnyChart)
    {{-- ── Analytics toggle ─────────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center mb-3">
        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                type="button"
                id="exp-analytics-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#exp-analytics"
                aria-expanded="false"
                aria-controls="exp-analytics">
            <i class="fas fa-chevron-right fa-fw"
               id="exp-analytics-chevron"
               style="transition: transform 0.2s; font-size:.75rem;"></i>
            <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                Analytics
            </span>
        </button>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>

    {{-- ── Charts — collapsed by default ───────────────────────────────────── --}}
    <div class="collapse mb-3" id="exp-analytics">
        <div class="row g-3">

            @if($hasProcessChart)
                <div class="col-12 {{ $hasFileChart ? 'col-md-7' : '' }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-cogs me-1"></i> Process Types
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Count of each process type used in this study
                            </p>
                            <div id="chart-exp-processes"
                                 style="height:{{ min(60 + count($activitiesGroup) * 28, 420) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif

            @if($hasFileChart)
                <div class="col-12 {{ $hasProcessChart ? 'col-md-5' : '' }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-file-alt me-1"></i> File Types
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Distribution of file types in this study
                            </p>
                            <div id="chart-exp-filetypes" style="height:240px;"></div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endif

{{-- ── Original card — unchanged ───────────────────────────────────────────── --}}
<x-card-container>
    <x-show-standard-details :item="$experiment"/>
    @if(!is_null($experiment->sheet))
        <div class="mb-3">
            <span class="fs-10 grey-5">Loaded from Google Sheet:
                <a href="{{$experiment->sheet->url}}" target="_blank" class="no-underline">
                    {{$experiment->sheet->title}}
                </a>
            </span>
        </div>
    @elseif (!is_null($experiment->loaded_file_path))
        <div class="mb-3">
            <span class="fs-10 grey-5">Loaded from file:
                <a href="{{route('projects.files.by-path', [$project, 'path' => $experiment->loaded_file_path])}}"
                   class="no-underline">{{$experiment->loaded_file_path}}</a>
            </span>
        </div>
    @endif
    @include('partials.overview._overview')
</x-card-container>

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
                    // sort descending for chart
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

                // Analytics toggle — chevron + localStorage
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
