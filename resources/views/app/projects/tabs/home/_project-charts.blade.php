{{--
  Prototype v2 project-level charts using Plotly (already in app.blade.php).
  Included inside the collapsible Analytics section in home-v2.blade.php.

  Three charts:
    1. Project Composition — horizontal log-scale bar so items spanning orders
                             of magnitude (50k files vs 5 studies) are all visible.
    2. Dataset Status       — donut: published vs. unpublished datasets.
    3. Storage gauge        — Plotly radial indicator; auto-scales unit (KB/MB/GB/TB).
                             Shows avg file size as a subtitle derived from
                             project->size / file_count — no extra queries.

  All data comes from the $project model.
--}}
<div class="row g-3 mb-2">

    {{-- Chart 1: Project Composition --}}
    <div class="col-12 col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-layer-group me-1"></i> Project Composition
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Log scale — hover a bar for exact count
                </p>
                <div id="chart-proj-composition" style="height:220px;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 2: Dataset Status --}}
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-database me-1"></i> Dataset Status
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Published vs. unpublished datasets
                </p>
                <div id="chart-proj-datasets" style="height:220px;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 3: Dataset Timeline --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-calendar-alt me-1"></i> Dataset Timeline
                </h6>
                @php
                    $dsTimeline = $project->datasets()->select(['id', 'created_at', 'published_at'])->orderBy('created_at')->get();
                    $dsCreatedByMonth   = [];
                    $dsPublishedByMonth = [];
                    $dsAllMonths        = [];
                    foreach ($dsTimeline as $ds) {
                        $mk = $ds->created_at->format('Y-m');
                        $dsCreatedByMonth[$mk] = ($dsCreatedByMonth[$mk] ?? 0) + 1;
                        $dsAllMonths[$mk]      = true;
                        if ($ds->published_at) {
                            $mk = $ds->published_at->format('Y-m');
                            $dsPublishedByMonth[$mk] = ($dsPublishedByMonth[$mk] ?? 0) + 1;
                            $dsAllMonths[$mk]        = true;
                        }
                    }
                    ksort($dsAllMonths);
                    $dsMonthLabels     = array_keys($dsAllMonths);
                    $dsCreatedValues   = array_map(fn($m) => $dsCreatedByMonth[$m]   ?? 0, $dsMonthLabels);
                    $dsPublishedValues = array_map(fn($m) => $dsPublishedByMonth[$m] ?? 0, $dsMonthLabels);
                @endphp
                <p class="text-muted mb-1" style="font-size:.7rem;">Datasets created and published per month</p>
                <div id="chart-proj-dataset-timeline" style="height:220px;"></div>
            </div>
        </div>
    </div>

    @php
        $hasFileTypes    = isset($fileDescriptionTypes) && count($fileDescriptionTypes) > 0;
        $hasProcessTypes = isset($activitiesGroup)       && count($activitiesGroup)       > 0;
    @endphp

    @if($hasFileTypes || $hasProcessTypes)
        <div class="row g-3 mb-4">

            {{-- File Types chart --}}
            @if($hasFileTypes)
                @php
                    // Sort descending by count, take top 20 so the chart doesn't get unreadable.
                    arsort($fileDescriptionTypes);
                    $ftTypes  = array_keys(array_slice($fileDescriptionTypes, 0, 20, true));
                    $ftCounts = array_values(array_slice($fileDescriptionTypes, 0, 20, true));
                    $ftTotal  = array_sum($fileDescriptionTypes);
                    $ftShown  = count($ftTypes);
                    $ftMore   = count($fileDescriptionTypes) - $ftShown; // how many were trimmed
                @endphp
                <div class="col-12 {{ $hasProcessTypes ? 'col-md-7' : '' }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-file-alt me-1"></i> File Types
                                <span
                                    class="badge bg-light text-secondary ms-1">{{ number_format($ftTotal) }} files</span>
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Distribution across {{ count($fileDescriptionTypes) }} extension(s)
                                @if($ftMore > 0)
                                    &nbsp;· showing top {{ $ftShown }}, {{ $ftMore }} more not shown
                                @endif
                            </p>
                            <div id="chart-file-types" style="height:{{ min(60 + $ftShown * 28, 480) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Process Types chart --}}
            @if($hasProcessTypes)
                @php
                    // Sort by count descending, cap at 20.
                    $agSorted = collect($activitiesGroup)->sortByDesc('count')->take(20)->values();
                    $agNames  = $agSorted->pluck('name')->map(fn($n) => strlen($n) > 30 ? substr($n,0,28).'…' : $n)->values()->toArray();
                    $agCounts = $agSorted->pluck('count')->values()->toArray();
                    $agTotal  = collect($activitiesGroup)->sum('count');
                    $agMore   = count($activitiesGroup) - count($agNames);
                @endphp
                <div class="col-12 {{ $hasFileTypes ? 'col-md-5' : '' }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-cogs me-1"></i> Process Types
                                <span
                                    class="badge bg-light text-secondary ms-1">{{ number_format($agTotal) }} total</span>
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                {{ count($activitiesGroup) }} distinct process type(s)
                                @if($agMore > 0)
                                    &nbsp;· showing top {{ count($agNames) }}, {{ $agMore }} more not shown
                                @endif
                            </p>
                            <div id="chart-process-types"
                                 style="height:{{ min(60 + count($agNames) * 28, 480) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    @else
        {{-- Neither data set has content yet --}}
        <div class="text-center py-5 text-muted">
            <i class="fas fa-database mb-3" style="font-size:2.5rem; opacity:.3;"></i>
            <p>No file or process data loaded yet.<br>
                <a href="{{route('projects.upload-files', [$project])}}">Upload files</a> or
                <a href="{{route('projects.experiments.create', [$project])}}">create a study</a>
                to populate this tab.
            </p>
        </div>
    @endif

</div>

@push('scripts')
    <script>
        (function () {
            const plotConfig = {responsive: true, displayModeBar: false};
            const base = (extra) => Object.assign({
                margin: {t: 10, b: 10, l: 10, r: 10},
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: {family: 'inherit', size: 11},
                showlegend: false,
            }, extra);

            // ── 1. Project Composition (log-scale horizontal bar) ─────────────────
            // Log scale means a project with 50k files AND 5 studies will show both
            // bars at readable widths. Zero-value items get a floor of 0.5 so they
            // still appear as a thin sliver rather than vanishing entirely.
            @php
                $compLabels = ['Files', 'Directories', 'Studies', 'Samples', 'Pub. Datasets', 'Unpub. Datasets'];
                $compValues = [
                    $project->file_count,
                    $project->directory_count,
                    $project->experiments_count,
                    $project->entities_count,
                    $project->published_datasets_count,
                    $project->unpublished_datasets_count,
                ];
                $compColors = ['#0d6efd', '#6ea8fe', '#0dcaf0', '#20c997', '#198754', '#ffc107'];
                // Log-scale floor so zero values show as a hairline rather than nothing.
                $compPlotValues = array_map(fn($v) => max($v, 0.5), $compValues);
                // Human-readable hover labels with the real counts.
                $compHover = array_map(
                    fn($l, $v) => $l . ': ' . number_format($v),
                    $compLabels, $compValues
                );
            @endphp
            // Explicit tick values at each power of 10 up to the max value in the data.
            // This avoids Plotly auto-generating intermediate ticks that crowd together.
            @php
                $maxVal    = max(1, ...$compValues);
                $logMax    = (int) floor(log10($maxVal)) + 1;
                $tickVals  = [];
                $tickTexts = [];
                $suffixes  = ['', 'k', 'M', 'B'];
                for ($p = 0; $p <= $logMax; $p++) {
                    $v = pow(10, $p);
                    $tickVals[]  = $v;
                    $exp3        = (int) floor($p / 3);
                    $mantissa    = $v / pow(10, $exp3 * 3);
                    $suffix      = $suffixes[min($exp3, count($suffixes) - 1)] ?? '';
                    $tickTexts[] = ($mantissa == (int)$mantissa ? (int)$mantissa : $mantissa) . $suffix;
                }
            @endphp
            Plotly.newPlot('chart-proj-composition', [{
                type: 'bar',
                orientation: 'h',
                y:             @json($compLabels),
                x:             @json($compPlotValues),
                marker: {color: @json($compColors) },
                customdata:    @json($compValues),
                hovertemplate: '%{y}: %{customdata:,}<extra></extra>',
            }], base({
                margin: {t: 10, b: 35, l: 110, r: 20},
                xaxis: {
                    type: 'log',
                    tickmode: 'array',
                    tickvals:  @json($tickVals),
                    ticktext:  @json($tickTexts),
                    tickfont: {size: 9},
                    gridcolor: '#dee2e6',
                },
                yaxis: {
                    autorange: 'reversed',
                    tickfont: {size: 10},
                },
            }), plotConfig);

            // ── 2. Dataset Status ─────────────────────────────────────────────────
            @php
                $pubCount   = $project->published_datasets_count;
                $unpubCount = $project->unpublished_datasets_count;
            @endphp
            @if($pubCount + $unpubCount > 0)
            Plotly.newPlot('chart-proj-datasets', [{
                type: 'pie',
                hole: 0.55,
                labels: ['Published', 'Unpublished'],
                values: [{{ $pubCount }}, {{ $unpubCount }}],
                marker: {colors: ['#198754', '#ffc107']},
                textinfo: 'value',
                hoverinfo: 'label+value+percent',
            }], base({
                showlegend: true,
                legend: {orientation: 'h', y: -0.15, font: {size: 10}},
            }), plotConfig);
            @else
            document.getElementById('chart-proj-datasets').innerHTML =
                '<p class="text-muted text-center pt-5 small">No datasets yet</p>';
            @endif

            // ── 3. Dataset Timeline ───────────────────────────────────────────────
            @if(count($dsMonthLabels) > 0)
            const dsMonthLabels = @json($dsMonthLabels);
            Plotly.newPlot('chart-proj-dataset-timeline', [
                {
                    type: 'bar',
                    name: 'Created',
                    x: dsMonthLabels,
                    y: @json($dsCreatedValues),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{x}: %{y} created<extra></extra>',
                },
                {
                    type: 'bar',
                    name: 'Published',
                    x: dsMonthLabels,
                    y: @json($dsPublishedValues),
                    marker: {color: '#198754'},
                    hovertemplate: '%{x}: %{y} published<extra></extra>',
                },
            ], base({
                barmode: 'group',
                margin: {t: 10, b: 45, l: 30, r: 10},
                showlegend: true,
                legend: {orientation: 'h', x: 0.5, xanchor: 'center', y: -0.25, font: {size: 10}},
                xaxis: {tickangle: -35, tickfont: {size: 9}},
                yaxis: {tickformat: 'd', dtick: 1, tickfont: {size: 9}},
            }), plotConfig);
            @else
            document.getElementById('chart-proj-dataset-timeline').innerHTML =
                '<p class="text-muted text-center pt-5 small">No datasets yet</p>';
            @endif

            // ── File Types horizontal bar ─────────────────────────────────────────
            @if($hasFileTypes)
            Plotly.newPlot('chart-file-types', [{
                type:          'bar',
                orientation:   'h',
                y:             @json($ftTypes),
                x:             @json($ftCounts),
                marker:        { color: '#0d6efd' },
                hovertemplate: '.%{y}: %{x:,} file(s)<extra></extra>',
                text:          @json(array_map(fn($c) => number_format($c), $ftCounts)),
                textposition:  'auto',
                textfont:      { size: 10 },
            }], base({
                margin: { t: 5, b: 30, l: 55, r: 20 },
                xaxis:  { type: 'log', title: { text: 'files (log scale)', font: { size: 10 } }, tickfont: { size: 9 }, gridcolor: '#dee2e6' },
                yaxis:  { autorange: 'reversed', tickfont: { size: 10 } },
            }), plotConfig);
            @endif

            // ── Process Types horizontal bar ──────────────────────────────────────
            @if($hasProcessTypes)
            Plotly.newPlot('chart-process-types', [{
                type:          'bar',
                orientation:   'h',
                y:             @json($agNames),
                x:             @json($agCounts),
                marker:        { color: '#0dcaf0' },
                hovertemplate: '%{y}: %{x:,}<extra></extra>',
                text:          @json(array_map(fn($c) => number_format($c), $agCounts)),
                textposition:  'auto',
                textfont:      { size: 10 },
            }], base({
                margin: { t: 5, b: 30, l: 155, r: 20 },
                xaxis:  { type: 'log', title: { text: 'count (log scale)', font: { size: 10 } }, tickfont: { size: 9 }, gridcolor: '#dee2e6' },
                yaxis:  { autorange: 'reversed', tickfont: { size: 10 } },
            }), plotConfig);
            @endif

        })();
    </script>
@endpush
