@extends('layouts.app')

@section('pageTitle', "{$project->name} - Datasets")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.index', $project))

@section('content')
    @php
        $dsAnalyticsKey  = 'mc_proj_' . $project->id . '_datasets_analytics';
        $totalDatasets   = count($datasets);
        $publishedCount  = 0;
        $unpublishedCount = 0;
        $tagCounts       = [];
        $pubMonths       = [];
        $actMonths       = [];

        foreach ($datasets as $ds) {
            if ($ds->published_at !== null) {
                $publishedCount++;
                $mk = $ds->published_at->format('Y-m');
                $pubMonths[$mk] = ($pubMonths[$mk] ?? 0) + 1;
            } else {
                $unpublishedCount++;
            }
            foreach ($ds->tags as $tag) {
                $tagCounts[$tag->name] = ($tagCounts[$tag->name] ?? 0) + 1;
            }
            $ak = $ds->updated_at->format('Y-m');
            $actMonths[$ak] = ($actMonths[$ak] ?? 0) + 1;
        }

        arsort($tagCounts);
        $tagLabels = array_keys(array_slice($tagCounts, 0, 15, true));
        $tagValues = array_values(array_slice($tagCounts, 0, 15, true));

        ksort($pubMonths);
        $pubMonthLabels = array_keys($pubMonths);
        $pubMonthValues = array_values($pubMonths);

        ksort($actMonths);
        $actMonthLabels = array_keys($actMonths);
        $actMonthValues = array_values($actMonths);
    @endphp

    @if(isInBeta('dashboard-charts'))
        {{-- ══ KPI strip — always visible ═══════════════════════════════════════════ --}}
        <div class="row g-2 mb-3">
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Total Datasets</div>
                    <div class="fw-bold fs-5 text-primary">{{ number_format($totalDatasets) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">in this project</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Published</div>
                    <div class="fw-bold fs-5 text-success">{{ number_format($publishedCount) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">publicly available</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Unpublished</div>
                    <div class="fw-bold fs-5 text-warning">{{ number_format($unpublishedCount) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">drafts / private</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Unique Tags</div>
                    <div class="fw-bold fs-5 text-info">{{ number_format(count($tagCounts)) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">across all datasets</div>
                </div>
            </div>
        </div>

        {{-- ══ Analytics — collapsible, default CLOSED ═══════════════════════════════ --}}
        @if($totalDatasets > 0)
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
            <div class="collapse mb-3" id="ds-analytics">
                <div class="row g-3">

                    {{-- Chart 1: Published vs Unpublished --}}
                    <div class="col-12 col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-database me-1"></i> Publication Status
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">
                                    Published vs unpublished datasets
                                </p>
                                <div id="chart-ds-status" style="height:220px;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Chart 2: Tags --}}
                    @if(count($tagLabels) > 0)
                        <div class="col-12 col-md-5">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-muted mb-0">
                                        <i class="fas fa-tags me-1"></i> Tag Usage
                                    </h6>
                                    <p class="text-muted mb-1" style="font-size:.7rem;">
                                        How many datasets use each tag
                                        @if(count($tagCounts) > 15)
                                            , showing top 15
                                        @endif
                                    </p>
                                    <div id="chart-ds-tags"
                                         style="height:{{ min(60 + count($tagLabels) * 26, 420) }}px;"></div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Chart 3: Activity timeline --}}
                    @if(count($actMonthLabels) > 1)
                        <div class="col-12 col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-muted mb-0">
                                        <i class="fas fa-calendar-alt me-1"></i> Activity Timeline
                                    </h6>
                                    <p class="text-muted mb-1" style="font-size:.7rem;">
                                        Datasets updated per month
                                    </p>
                                    <div id="chart-ds-activity" style="height:220px;"></div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Chart 4: Publication timeline --}}
                    @if(count($pubMonthLabels) > 1)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-muted mb-0">
                                        <i class="fas fa-share-square me-1"></i> Publication Timeline
                                    </h6>
                                    <p class="text-muted mb-1" style="font-size:.7rem;">
                                        Number of datasets published per month
                                    </p>
                                    <div id="chart-ds-published" style="height:180px;"></div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endif {{-- totalDatasets > 0 --}}
    @endif
    <a class="action-link float-end" href="{{route('projects.datasets.create', [$project])}}">
        <i class="fas fa-plus me-2"></i>Create Dataset
    </a>
    <br>
    <br/>
    <table id="datasets" class="table table-hover" style="width:100%">
        <thead class="table-light">
        <tr>
            <th>Dataset</th>
            <th>Summary</th>
            <th>Tags</th>
            <th>Published</th>
            <th>Updated</th>
            <th>Date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($datasets as $dataset)
            <tr>
                <td>
                    <a href="{{route('projects.datasets.show.overview', [$project, $dataset])}}"
                       class="no-underline">
                        {{$dataset->name}}
                    </a>
                </td>
                <td>{{$dataset->summary}}</td>
                <td>
                    @foreach($dataset->tags as $tag)
                        <span class="badge text-bg-info ms-1 text-white">{{$tag->name}}</span>
                    @endforeach
                </td>
                @if ($dataset->published_at === null)
                    <td>Not published</td>
                @else
                    <td>{{$dataset->published_at->diffForHumans()}}</td>
                @endif
                <td>{{$dataset->updated_at->diffForHumans()}}</td>
                <td>{{$dataset->updated_at}}</td>
                <td>
                    <div class="float-end">
                        <a href="{{route('projects.datasets.show.overview', [$project, $dataset])}}"
                           class="action-link">
                            <i class="fas fa-fw fa-eye"></i>
                        </a>
                        <a href="{{route('projects.datasets.edit', [$project, $dataset])}}"
                           class="action-link">
                            <i class="fas fa-fw fa-edit"></i>
                        </a>
                        @if(is_null($dataset->published_at))
                            <a href="{{route('projects.datasets.delete', [$project, $dataset])}}"
                               class="action-link">
                                <i class="fas fa-fw fa-trash-alt"></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            document.addEventListener('livewire:navigating', () => {
                $('#datasets').DataTable().destroy();
            }, {once: true});

            $(document).ready(() => {
                $('#datasets').DataTable({
                    pageLength: 100,
                    stateSave: true,
                    columnDefs: [
                        {orderData: [5], targets: [4]},
                        {targets: [5], visible: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const STORAGE_KEY = '{{ $dsAnalyticsKey }}';
                const panel = document.getElementById('ds-analytics');
                const toggle = document.getElementById('ds-analytics-toggle');
                const chevron = document.getElementById('ds-analytics-chevron');

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
                    paper_bgcolor: 'transparent',
                    plot_bgcolor: 'transparent',
                    font: {family: 'inherit', size: 11},
                    showlegend: false,
                }, extra);

                // Chart 1: Publication status donut
                Plotly.newPlot('chart-ds-status', [{
                    type: 'pie',
                    hole: 0.5,
                    labels: ['Published', 'Unpublished'],
                    values: [{{ $publishedCount }}, {{ $unpublishedCount }}],
                    marker: {colors: ['#198754', '#ffc107']},
                    textinfo: 'label+value',
                    hoverinfo: 'label+value+percent',
                    textfont: {size: 10},
                }], base({
                    margin: {t: 10, b: 10, l: 10, r: 10},
                }), plotConfig);

                @if(count($tagLabels) > 0)
                // Chart 2: Tag usage
                Plotly.newPlot('chart-ds-tags', [{
                    type: 'bar',
                    orientation: 'h',
                    y:    @json($tagLabels),
                    x:    @json($tagValues),
                    marker: {color: '#0dcaf0'},
                    hovertemplate: '%{y}: %{x:,} dataset(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $tagValues)),
                    textposition: 'inside',
                    insidetextanchor: 'end',
                    textfont: {color: '#000', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 130, r: 20},
                    xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                @if(count($actMonthLabels) > 1)
                // Chart 3: Activity timeline
                Plotly.newPlot('chart-ds-activity', [{
                    type: 'bar',
                    x:    @json($actMonthLabels),
                    y:    @json($actMonthValues),
                    marker: {color: '#6f42c1'},
                    hovertemplate: '%{x}: %{y:,} dataset(s)<extra></extra>',
                }], base({
                    margin: {t: 10, b: 55, l: 40, r: 10},
                    xaxis: {tickangle: -45, tickfont: {size: 9}},
                    yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                }), plotConfig);
                @endif

                @if(count($pubMonthLabels) > 1)
                // Chart 4: Publication timeline
                Plotly.newPlot('chart-ds-published', [{
                    type: 'bar',
                    x:    @json($pubMonthLabels),
                    y:    @json($pubMonthValues),
                    marker: {color: '#198754'},
                    hovertemplate: '%{x}: %{y:,} published<extra></extra>',
                }], base({
                    margin: {t: 10, b: 55, l: 40, r: 10},
                    xaxis: {tickangle: -45, tickfont: {size: 9}},
                    yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                }), plotConfig);
                @endif

            })();
        </script>
    @endpush
@stop
