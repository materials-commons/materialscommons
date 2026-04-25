{{--
  Prototype v2 dashboard charts using Plotly (already loaded in app.blade.php).
  Row 1:
    1. Project ownership donut (yours vs. shared)
    2. Health status donut (OK / Warning / Error)
    3. Top projects by file count (horizontal bar)
  Row 2:
    4. Project activity over time — bar chart of how many projects were
       updated each month for the last 12 months (col-md-8)
    5. Storage treemap — each project as a tile sized by disk usage (col-md-4)
--}}
<div class="row g-3 mb-4">

    {{-- Chart 1: Project Ownership --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-pie-chart me-1"></i> Project Ownership
                </h6>
                <div id="chart-ownership" style="height:200px;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 2: Health Status --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-heartbeat me-1"></i> Project Health
                </h6>
                <div id="chart-health" style="height:200px;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 3: Top Projects by File Count --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-bars me-1"></i> Top Projects by Files
                </h6>
                <div id="chart-top-projects" style="height:200px;"></div>
            </div>
        </div>
    </div>

</div>

{{-- ── Row 2: Activity over time + Storage treemap ────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Chart 4: Activity over time --}}
    <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-chart-bar me-1"></i> Project Activity (last 12 months)
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Number of projects updated each month
                </p>
                <div id="chart-activity" style="height:220px;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 5: Storage treemap --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-hdd me-1"></i> Storage by Project
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Tile size = disk usage (hover for details)
                </p>
                <div id="chart-storage" style="height:220px;"></div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    // ── shared Plotly config ───────────────────────────────────────────────
    const plotConfig = { responsive: true, displayModeBar: false };
    const plotLayout = (extra) => Object.assign({
        margin: { t: 10, b: 10, l: 10, r: 10 },
        paper_bgcolor: 'transparent',
        plot_bgcolor:  'transparent',
        font: { family: 'inherit', size: 11 },
        showlegend: true,
        legend: { orientation: 'h', y: -0.15, font: { size: 10 } },
    }, extra);

    // ── 1. Ownership donut ────────────────────────────────────────────────
    const ownershipData = [{
        type:   'pie',
        hole:   0.55,
        labels: ['Your Projects', 'Shared With You'],
        values: [{{ $userProjectsCount }}, {{ $otherProjectsCount }}],
        marker: { colors: ['#0d6efd', '#6ea8fe'] },
        textinfo:    'value',
        hoverinfo:   'label+value+percent',
    }];
    Plotly.newPlot('chart-ownership', ownershipData, plotLayout(), plotConfig);

    // ── 2. Health status donut ────────────────────────────────────────────
    @php
        $healthyCount = count($projects) - $projectsWithErrorStateCount - $projectsWithWarningStateCount;
        $healthyCount = max(0, $healthyCount);
    @endphp
    const healthData = [{
        type:   'pie',
        hole:   0.55,
        labels: ['Healthy', 'Warnings', 'Problems'],
        values: [{{ $healthyCount }}, {{ $projectsWithWarningStateCount }}, {{ $projectsWithErrorStateCount }}],
        marker: { colors: ['#198754', '#ffc107', '#dc3545'] },
        textinfo:    'value',
        hoverinfo:   'label+value+percent',
    }];
    Plotly.newPlot('chart-health', healthData, plotLayout(), plotConfig);

    // ── 3. Top projects by file count (horizontal bar) ────────────────────
    @php
        // Sort by file_count descending, take top 8
        $topProjects = collect($projects)
            ->sortByDesc('file_count')
            ->take(8)
            ->values();
        $barNames  = $topProjects->pluck('name')->map(fn($n) => strlen($n) > 20 ? substr($n, 0, 18).'…' : $n)->values()->toArray();
        $barValues = $topProjects->pluck('file_count')->values()->toArray();
    @endphp
    const topData = [{
        type:        'bar',
        orientation: 'h',
        x: @json($barValues),
        y: @json($barNames),
        marker: { color: '#0d6efd' },
        hovertemplate: '%{y}: %{x:,} files<extra></extra>',
    }];
    Plotly.newPlot('chart-top-projects', topData, plotLayout({
        margin:  { t: 10, b: 30, l: 130, r: 20 },
        showlegend: false,
        xaxis: { title: { text: 'Files', font: { size: 10 } }, tickformat: ',d' },
        yaxis: { autorange: 'reversed' },
    }), plotConfig);

    // ── 4. Project activity over the last 12 months ───────────────────────
    @php
        // Build an ordered list of the last 12 month keys (YYYY-MM)
        $activityMonthKeys   = [];
        $activityMonthLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $activityMonthKeys[]   = $m->format('Y-m');
            $activityMonthLabels[] = $m->format('M Y');
        }
        // Count projects whose updated_at falls in each month bucket
        $updatesByMonth = collect($projects)
            ->groupBy(fn($p) => \Carbon\Carbon::parse($p->updated_at)->format('Y-m'))
            ->map->count();
        $activityValues = array_map(
            fn($k) => $updatesByMonth->get($k, 0),
            $activityMonthKeys
        );
    @endphp
    const activityData = [{
        type: 'bar',
        x:    @json($activityMonthLabels),
        y:    @json($activityValues),
        marker: {
            color: @json($activityValues),
            colorscale: [[0, '#cfe2ff'], [1, '#0d6efd']],
            showscale: false,
        },
        hovertemplate: '%{x}: %{y} project(s) updated<extra></extra>',
    }];
    Plotly.newPlot('chart-activity', activityData, plotLayout({
        margin:     { t: 10, b: 60, l: 35, r: 10 },
        showlegend: false,
        xaxis: { tickangle: -35, tickfont: { size: 9 } },
        yaxis: { tickformat: 'd', dtick: 1 },
    }), plotConfig);

    // ── 5. Storage treemap ────────────────────────────────────────────────
    @php
        // Take up to 20 projects that have non-zero size, largest first
        $storageProjects = collect($projects)
            ->filter(fn($p) => $p->size > 0)
            ->sortByDesc('size')
            ->take(20)
            ->values();
        $storageLabels = $storageProjects
            ->map(fn($p) => strlen($p->name) > 18 ? substr($p->name, 0, 16).'…' : $p->name)
            ->values()->toArray();
        $storageValues = $storageProjects->pluck('size')->values()->toArray();
        // Human-readable sizes for hover text
        $storageHover = $storageProjects
            ->map(fn($p) => $p->name . '<br>' . formatBytes($p->size))
            ->values()->toArray();
    @endphp
    @if(count($storageValues) > 0)
    const storageData = [{
        type:    'treemap',
        labels:  @json($storageLabels),
        parents: @json(array_fill(0, count($storageLabels), '')),
        values:  @json($storageValues),
        text:    @json($storageHover),
        hovertemplate: '%{text}<extra></extra>',
        textfont: { size: 10 },
        marker: {
            colorscale: 'Blues',
            colors: @json($storageValues),
            showscale: false,
        },
    }];
    Plotly.newPlot('chart-storage', storageData, plotLayout({
        margin:     { t: 0, b: 0, l: 0, r: 0 },
        showlegend: false,
    }), plotConfig);
    @else
    document.getElementById('chart-storage').innerHTML =
        '<p class="text-muted text-center pt-4">No storage data yet</p>';
    @endif

})();
</script>
@endpush
