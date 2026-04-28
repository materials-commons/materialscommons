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
            <div class="card-body p-3">
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
            <div class="card-body p-3">
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

    {{-- Chart 3: Storage gauge --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-hdd me-1"></i> Storage Used
                </h6>
                @php
                    $avgFileSize = ($project->file_count > 0 && ($project->size ?? 0) > 0)
                        ? formatBytes(intdiv($project->size, $project->file_count)) . ' avg/file'
                        : null;
                    // Compute gauge values here so we can use them in the subtitle too
                    $sizeBytes = $project->size ?? 0;
                    if ($sizeBytes >= 1024 ** 4) {
                        $gaugeValue = round($sizeBytes / 1024 ** 4, 2);
                        $gaugeUnit  = 'TB';
                    } elseif ($sizeBytes >= 1024 ** 3) {
                        $gaugeValue = round($sizeBytes / 1024 ** 3, 2);
                        $gaugeUnit  = 'GB';
                    } elseif ($sizeBytes >= 1024 ** 2) {
                        $gaugeValue = round($sizeBytes / 1024 ** 2, 1);
                        $gaugeUnit  = 'MB';
                    } else {
                        $gaugeValue = round($sizeBytes / 1024, 1);
                        $gaugeUnit  = 'KB';
                    }
                    $rawMax   = max($gaugeValue * 1.5, 1);
                    $mag      = pow(10, floor(log10($rawMax)));
                    $gaugeMax = ceil($rawMax / $mag) * $mag;
                @endphp
                <p class="text-muted mb-0" style="font-size:.7rem;">
                    @if($avgFileSize)
                        {{ $avgFileSize }}
                        &nbsp;·&nbsp; gauge scale: 0 – {{ $gaugeMax }} {{ $gaugeUnit }}
                    @else
                        No files uploaded yet
                    @endif
                </p>
                <div id="chart-proj-storage" style="height:220px;"></div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    const plotConfig = { responsive: true, displayModeBar: false };
    const base = (extra) => Object.assign({
        margin:        { t: 10, b: 10, l: 10, r: 10 },
        paper_bgcolor: 'transparent',
        plot_bgcolor:  'transparent',
        font:          { family: 'inherit', size: 11 },
        showlegend:    false,
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
        type:          'bar',
        orientation:   'h',
        y:             @json($compLabels),
        x:             @json($compPlotValues),
        marker:        { color: @json($compColors) },
        customdata:    @json($compValues),
        hovertemplate: '%{y}: %{customdata:,}<extra></extra>',
    }], base({
        margin: { t: 10, b: 35, l: 110, r: 20 },
        xaxis: {
            type:      'log',
            tickmode:  'array',
            tickvals:  @json($tickVals),
            ticktext:  @json($tickTexts),
            tickfont:  { size: 9 },
            gridcolor: '#dee2e6',
        },
        yaxis: {
            autorange: 'reversed',
            tickfont:  { size: 10 },
        },
    }), plotConfig);

    // ── 2. Dataset Status ─────────────────────────────────────────────────
    @php
        $pubCount   = $project->published_datasets_count;
        $unpubCount = $project->unpublished_datasets_count;
    @endphp
    @if($pubCount + $unpubCount > 0)
    Plotly.newPlot('chart-proj-datasets', [{
        type:      'pie',
        hole:      0.55,
        labels:    ['Published', 'Unpublished'],
        values:    [{{ $pubCount }}, {{ $unpubCount }}],
        marker:    { colors: ['#198754', '#ffc107'] },
        textinfo:  'value',
        hoverinfo: 'label+value+percent',
    }], base({
        showlegend: true,
        legend:     { orientation: 'h', y: -0.15, font: { size: 10 } },
    }), plotConfig);
    @else
    document.getElementById('chart-proj-datasets').innerHTML =
        '<p class="text-muted text-center pt-5 small">No datasets yet</p>';
    @endif

    // ── 3. Storage radial gauge ───────────────────────────────────────────
    // Unit selection and gauge max are computed in PHP above (in the card
    // subtitle block) so they stay in sync with the subtitle text.
    // Single-colour bar on a plain grey arc — no coloured bands,
    // which previously looked meaningful but weren't.
    @if($sizeBytes > 0)
    Plotly.newPlot('chart-proj-storage', [{
        type:  'indicator',
        mode:  'gauge+number',
        value: {{ $gaugeValue }},
        number: {
            suffix:      ' {{ $gaugeUnit }}',
            font:        { size: 26 },
            valueformat: '.2f',
        },
        gauge: {
            axis: {
                range:      [0, {{ $gaugeMax }}],
                ticksuffix: ' {{ $gaugeUnit }}',
                tickfont:   { size: 9 },
                nticks:     4,   // 0, 1/3, 2/3, max — never crowd
            },
            bar:         { color: '#0d6efd', thickness: 0.65 },
            bgcolor:     '#e9ecef',   // plain grey arc — no misleading colour bands
            borderwidth: 0,
        },
    }], base({
        margin: { t: 20, b: 10, l: 25, r: 25 },
    }), plotConfig);
    @else
    document.getElementById('chart-proj-storage').innerHTML =
        '<p class="text-muted text-center pt-5 small">No files uploaded yet</p>';
    @endif
})();
</script>
@endpush
