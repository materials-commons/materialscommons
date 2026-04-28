{{--
  Prototype v2 Samples / Computations index page.
  To try it: in index.blade.php change
      @include('partials.entities._entities-with-used-activities-table', ...)
  to
      @include('partials.entities._entities-with-used-activities-table-v2', ...)
  OR swap this entire file in as the view returned by the controller.

  Adds above the existing content:
    • KPI strip  — total samples, processes, avg process depth, studies
    • ▶ Analytics (collapsible, default CLOSED, per-project localStorage)
        Chart 1 — Process Participation bar (which processes cover the most samples)
        Chart 2 — Sample Depth histogram   (how thoroughly each sample is characterised)
        Chart 3 — Samples per Study        (only when $showExperiment is true)

  All computed from $entities / $activities / $usedActivities already in scope.
  No extra queries.
--}}

@extends('layouts.app')

@section('pageTitle', "{$project->name} - Samples")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@if(Request::routeIs('projects.entities.*'))
    @section('breadcrumbs', Breadcrumbs::render('projects.entities.index', $project))
@else
    @section('breadcrumbs', Breadcrumbs::render('projects.computations.entities.index', $project))
@endif

@section('content')
    @php
        use Illuminate\Support\Str;

        $totalSamples    = count($entities);
        $totalProcesses  = count($activities);
        $projAnalyticsKey = 'mc_proj_' . $project->id . '_' . ($category ?? 'exp') . '_samples_analytics';

        // ── Per-activity participation count ──────────────────────────────────
        // activities is ordered; usedActivities[$entityId][$activityIndex] = bool
        $processParticipation = [];   // activity name => sample count
        foreach ($activities as $i => $activity) {
            $count = 0;
            foreach ($usedActivities as $usedArr) {
                if (!empty($usedArr[$i])) {
                    $count++;
                }
            }
            $processParticipation[$activity->name] = $count;
        }
        // Sort descending so most-covered process is at the top
        arsort($processParticipation);
        $procNames  = array_keys(array_slice($processParticipation, 0, 20, true));
        $procCounts = array_values(array_slice($processParticipation, 0, 20, true));

        // ── Per-sample depth (how many processes each sample participated in) ──
        $depthBuckets = [];   // depth => count of samples
        $totalDepth   = 0;
        foreach ($entities as $entity) {
            $depth = 0;
            if (!empty($usedActivities[$entity->id])) {
                $depth = count(array_filter($usedActivities[$entity->id]));
            }
            $depthBuckets[$depth] = ($depthBuckets[$depth] ?? 0) + 1;
            $totalDepth += $depth;
        }
        ksort($depthBuckets);
        $depthLabels = array_map(fn($d) => $d === 0 ? 'None' : (string)$d, array_keys($depthBuckets));
        $depthValues = array_values($depthBuckets);
        $avgDepth    = $totalSamples > 0 ? round($totalDepth / $totalSamples, 1) : 0;

        // ── Samples per Study ─────────────────────────────────────────────────
        $samplesByStudy = [];
        if ($showExperiment ?? false) {
            foreach ($entities as $entity) {
                $studyName = (isset($entity->experiments) && $entity->experiments->count() > 0)
                    ? $entity->experiments[0]->name
                    : '(No Study)';
                $samplesByStudy[$studyName] = ($samplesByStudy[$studyName] ?? 0) + 1;
            }
            arsort($samplesByStudy);
        }
    @endphp

    {{-- ══════════════════════════════════════════════════════════════════════════
         KPI strip — always visible
         ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">{{ ucfirst($category ?? 'Total') }} Samples</div>
                <div class="fw-bold fs-5 text-primary">{{ number_format($totalSamples) }}</div>
                <div class="text-muted" style="font-size:.65rem;">in this project</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Process Types</div>
                <div class="fw-bold fs-5 text-info">{{ number_format($totalProcesses) }}</div>
                <div class="text-muted" style="font-size:.65rem;">unique activities</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Avg Process Depth</div>
                <div class="fw-bold fs-5 text-success">{{ $avgDepth }}</div>
                <div class="text-muted" style="font-size:.65rem;">processes per sample</div>
            </div>
        </div>
        @if($showExperiment ?? false)
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Studies</div>
                    <div class="fw-bold fs-5 text-warning">{{ number_format(count($samplesByStudy)) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">with samples</div>
                </div>
            </div>
        @else
            <div class="col-6 col-sm-3">
                <div class="card border-0 shadow-sm h-100 text-center py-2">
                    <div class="text-muted small">Fully Characterised</div>
                    @php $fullyCovered = $depthBuckets[0] ?? 0; $withProcesses = $totalSamples - $fullyCovered; @endphp
                    <div class="fw-bold fs-5 text-warning">{{ number_format($withProcesses) }}</div>
                    <div class="text-muted" style="font-size:.65rem;">have ≥1 process</div>
                </div>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════
         Analytics toggle
         ══════════════════════════════════════════════════════════════════════════ --}}
    @if($totalProcesses > 0 || count($samplesByStudy) > 0)
        <div class="d-flex align-items-center mb-2">
            <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                    type="button"
                    id="samples-analytics-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#samples-analytics"
                    aria-expanded="false"
                    aria-controls="samples-analytics">
                <i class="fas fa-chevron-right fa-fw" id="samples-analytics-chevron"
                   style="transition:transform .2s; font-size:.75rem;"></i>
                <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Analytics
        </span>
            </button>
            <hr class="flex-grow-1 ms-3 my-0 opacity-25">
        </div>
    @endif {{-- has charts --}}
    <div class="collapse mb-3" id="samples-analytics">
        <div class="row g-3">

            {{-- Chart 1: Process Participation ─────────────────────────────────── --}}
            @if($totalProcesses > 0)
                <div class="col-12 {{ (count($samplesByStudy) > 0) ? 'col-md-5' : 'col-md-7' }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-tasks me-1"></i> Process Participation
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                How many samples participated in each process — most covered first
                                @if(count($processParticipation) > 20)
                                    , showing top 20
                                @endif
                            </p>
                            <div id="chart-proc-participation"
                                 style="height:{{ min(60 + count($procNames) * 26, 480) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Chart 2: Sample Depth Histogram ─────────────────────────────────── --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted mb-0">
                            <i class="fas fa-layer-group me-1"></i> Sample Depth
                        </h6>
                        <p class="text-muted mb-1" style="font-size:.7rem;">
                            How many processes each sample participated in
                        </p>
                        <div id="chart-sample-depth" style="height:220px;"></div>
                    </div>
                </div>
            </div>

            {{-- Chart 3: Samples per Study (conditional) ────────────────────────── --}}
            @if(count($samplesByStudy) > 0)
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-flask me-1"></i> Samples per Study
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Distribution of samples across studies
                            </p>
                            <div id="chart-samples-study" style="height:220px;"></div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════════════════
         Existing content — unchanged
         ══════════════════════════════════════════════════════════════════════════ --}}
    @include('partials.entities._entities-with-used-activities-table', ['showExperiment' => $showExperiment])

    @push('scripts')
        <script>
            (function () {
                // ── Collapse persistence ──────────────────────────────────────────────
                const panel = document.getElementById('samples-analytics');
                const toggle = document.getElementById('samples-analytics-toggle');
                const chevron = document.getElementById('samples-analytics-chevron');
                const STORAGE_KEY = '{{ $projAnalyticsKey }}';

                if (panel) {
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
                    // Plotly charts rendered inside a hidden collapse have zero
                    // dimensions at init time.  Resize them once the panel is
                    // fully open and has real pixel dimensions.
                    panel.addEventListener('shown.bs.collapse', () => {
                        panel.querySelectorAll('.js-plotly-plot').forEach(div => {
                            Plotly.Plots.resize(div);
                        });
                    });
                }

                // ── Charts ────────────────────────────────────────────────────────────
                const plotConfig = {responsive: true, displayModeBar: false};
                const base = (extra) => Object.assign({
                    paper_bgcolor: 'transparent',
                    plot_bgcolor: 'transparent',
                    font: {family: 'inherit', size: 11},
                    showlegend: false,
                }, extra);

                @if($totalProcesses > 0)
                // Chart 1: Process participation bar
                Plotly.newPlot('chart-proc-participation', [{
                    type: 'bar',
                    orientation: 'h',
                    y:                @json($procNames),
                    x:                @json($procCounts),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{y}: %{x:,} sample(s)<extra></extra>',
                    text:             @json(array_map(fn($c) => number_format($c), $procCounts)),
                    textposition: 'inside',
                    insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 35, l: 160, r: 20},
                    xaxis: {
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'Samples', font: {size: 10}}
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                // Chart 2: Sample depth histogram
                @php
                    // Colour depth bars: grey for 0, gradient blue for ≥1
                    $depthColors = array_map(
                        fn($d) => $d === 0 ? '#adb5bd' : '#0d6efd',
                        array_keys($depthBuckets)
                    );
                @endphp
                Plotly.newPlot('chart-sample-depth', [{
                    type: 'bar',
                    x:             @json($depthLabels),
                    y:             @json($depthValues),
                    marker: {color: @json($depthColors) },
                    hovertemplate: '%{x} process(es): %{y:,} sample(s)<extra></extra>',
                    text:          @json(array_map(fn($v) => number_format($v), $depthValues)),
                    textposition: 'outside',
                    textfont: {size: 9},
                }], base({
                    margin: {t: 20, b: 40, l: 40, r: 15},
                    xaxis: {title: {text: '# Processes', font: {size: 10}}, tickfont: {size: 9}},
                    yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                }), plotConfig);

                @if(count($samplesByStudy) > 0)
                // Chart 3: Samples per study
                Plotly.newPlot('chart-samples-study', [{
                    type: 'pie',
                    hole: 0.45,
                    labels:    @json(array_keys($samplesByStudy)),
                    values:    @json(array_values($samplesByStudy)),
                    textinfo: 'label+value',
                    hoverinfo: 'label+value+percent',
                    textfont: {size: 9},
                }], base({
                    margin: {t: 10, b: 10, l: 10, r: 10},
                    showlegend: false,
                }), plotConfig);
                @endif
            })();
        </script>
    @endpush

@endsection
