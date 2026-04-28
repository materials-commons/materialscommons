@extends('layouts.app')

@section('pageTitle', "{$project->name} - Studies")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.index', $project))

@section('content')
    @php
        $expAnalyticsKey = 'mc_proj_' . $project->id . '_experiments_analytics';
        $experiments     = $project->experiments;
        $totalExps       = $experiments->count();
        $ownerCounts     = [];
        $actMonths       = [];

        foreach ($experiments as $exp) {
            $oName = $exp->owner->name;
            $ownerCounts[$oName] = ($ownerCounts[$oName] ?? 0) + 1;
            $mk = $exp->updated_at->format('Y-m');
            $actMonths[$mk] = ($actMonths[$mk] ?? 0) + 1;
        }

        arsort($ownerCounts);
        $ownerLabels  = array_keys($ownerCounts);
        $ownerValues  = array_values($ownerCounts);

        ksort($actMonths);
        $actMonthLabels = array_keys($actMonths);
        $actMonthValues = array_values($actMonths);

        $uniqueOwners = count($ownerCounts);
        $lastUpdated  = $experiments->max('updated_at');
    @endphp

    @if(isInBeta('dashboard-charts'))
        {{-- ══ KPI strip — always visible ═══════════════════════════════════════════ --}}
        <div class="row g-2 mb-3">
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Total Studies</div>
                    <div class="fw-bold fs-5 text-primary">{{ number_format($totalExps) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">in this project</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Contributors</div>
                    <div class="fw-bold fs-5 text-info">{{ number_format($uniqueOwners) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">unique owners</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Last Updated</div>
                    <div class="fw-bold fs-5 text-success" style="font-size:1rem !important;">
                        {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->diffForHumans() : '—' }}
                    </div>
                    <div class="text-muted" style="font-size:.65rem;">most recent activity</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Active Months</div>
                    <div class="fw-bold fs-5 text-warning">{{ number_format(count($actMonths)) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">months with activity</div>
                </div>
            </div>
        </div>

        {{-- ══ Analytics — collapsible, default CLOSED ═══════════════════════════════ --}}
        @if($totalExps > 0)
            <div class="d-flex align-items-center mb-2">
                <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                        type="button"
                        id="exp-analytics-toggle"
                        data-bs-toggle="collapse"
                        data-bs-target="#exp-analytics"
                        aria-expanded="false"
                        aria-controls="exp-analytics">
                    <i class="fas fa-chevron-right fa-fw" id="exp-analytics-chevron"
                       style="transition:transform .2s; font-size:.75rem;"></i>
                    <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                Analytics
            </span>
                </button>
                <hr class="flex-grow-1 ms-3 my-0 opacity-25">
            </div>
            <div class="collapse mb-3" id="exp-analytics">
                <div class="row g-3">

                    {{-- Chart 1: Studies per contributor --}}
                    @if(count($ownerLabels) > 0)
                        <div class="col-12 col-md-5">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-muted mb-0">
                                        <i class="fas fa-users me-1"></i> Studies per Contributor
                                    </h6>
                                    <p class="text-muted mb-1" style="font-size:.7rem;">
                                        How many studies each team member owns
                                    </p>
                                    <div id="chart-exp-owners"
                                         style="height:{{ min(60 + count($ownerLabels) * 30, 360) }}px;"></div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Chart 2: Activity timeline --}}
                    <div class="col-12 col-md-7">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-calendar-alt me-1"></i> Activity Timeline
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">
                                    Studies updated per month
                                </p>
                                <div id="chart-exp-activity" style="height:220px;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif {{-- totalExps > 0 --}}
    @endif
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="table-container">
                <div class="card table-card">
                    <div class="card-body inner-card">
                        <a class="action-link float-end"
                           href="{{route('projects.experiments.create', ['project' => $project->id])}}">
                            <i class="fas fa-plus me-2"></i>Create Study
                        </a>
                        <br/>
                        @include('app.projects.experiments._experiments-table')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @include('app.projects.experiments._experiment-info-card')
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const STORAGE_KEY = '{{ $expAnalyticsKey }}';
                const panel = document.getElementById('exp-analytics');
                const toggle = document.getElementById('exp-analytics-toggle');
                const chevron = document.getElementById('exp-analytics-chevron');

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

                @if(count($ownerLabels) > 0)
                Plotly.newPlot('chart-exp-owners', [{
                    type: 'bar',
                    orientation: 'h',
                    y:    @json($ownerLabels),
                    x:    @json($ownerValues),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{y}: %{x:,} study/studies<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $ownerValues)),
                    textposition: 'inside',
                    insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 130, r: 20},
                    xaxis: {
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'studies', font: {size: 10}}
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                Plotly.newPlot('chart-exp-activity', [{
                    type: 'bar',
                    x:    @json($actMonthLabels),
                    y:    @json($actMonthValues),
                    marker: {color: '#198754'},
                    hovertemplate: '%{x}: %{y:,} study/studies updated<extra></extra>',
                }], base({
                    margin: {t: 10, b: 55, l: 40, r: 10},
                    xaxis: {tickangle: -45, tickfont: {size: 9}},
                    yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                }), plotConfig);

            })();
        </script>
    @endpush

@stop
