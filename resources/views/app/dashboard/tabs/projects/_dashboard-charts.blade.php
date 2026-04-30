{{--
  Prototype v2 dashboard charts using Plotly (already loaded in app.blade.php).
  Row 1:
    1. Project ownership donut (yours vs. shared)     — click slice → modal listing those projects
    2. Health status donut (OK / Warning / Error)
    3. Top projects by file count (horizontal bar)    — log scale, click → open project
  Row 2:
    4. Project activity over time — bar chart of how many projects were
       updated each month for the last 12 months (col-md-8) — click bar → modal listing projects
    5. Storage treemap — each project as a tile sized by disk usage (col-md-4)
--}}

@php
    // Ownership modal data
    $yourProjectsList = collect($projects)
        ->filter(fn($p) => $p->owner_id === auth()->id())
        ->map(fn($p) => ['name' => $p->name, 'url' => route('projects.show', [$p->id])])
        ->values()->toArray();
    $sharedProjectsList = collect($projects)
        ->filter(fn($p) => $p->owner_id !== auth()->id())
        ->map(fn($p) => ['name' => $p->name, 'url' => route('projects.show', [$p->id])])
        ->values()->toArray();
@endphp

<div class="row g-3 mb-4">

    {{-- Chart 1: Project Ownership --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-pie-chart me-1"></i> Project Ownership
                </h6>
                <p class="text-muted mb-0" style="font-size:.7rem;">Click a slice to list those projects</p>
                <div id="chart-ownership" style="height:200px; width: 100%; cursor:pointer;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 2: Health Status --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-heartbeat me-1"></i> Project Health
                </h6>
                <div id="chart-health" style="height:200px; width: 100%;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 3: Top Projects by File Count --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-bars me-1"></i> Top Projects by Files
                </h6>
                <p class="text-muted mb-0" style="font-size:.7rem;">Click a bar to open the project</p>
                <div id="chart-top-projects" style="height:200px; cursor:pointer;"></div>
            </div>
        </div>
    </div>

</div>

{{-- ── Row 2: Activity over time + Storage treemap ────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Chart 4: Activity over time --}}
    <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-chart-bar me-1"></i> Project Activity (last 12 months)
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Number of projects updated each month — click a bar to list them
                </p>
                <div id="chart-activity" style="height:220px; width: 100%; cursor:pointer;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 5: Storage treemap --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-clock me-1"></i> Stale Projects
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Projects with the oldest recent activity — click to open
                </p>
                <div id="chart-stale-projects" style="height:220px; width:100%; cursor:pointer;"></div>
            </div>
        </div>
    </div>

</div>

{{-- Shared modal for ownership & activity drill-downs --}}
<div class="modal fade" id="dashboard-drilldown-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color" id="dashboard-drilldown-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="dashboard-drilldown-body" style="max-height:60vh; overflow-y:auto;"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    // ── modal helper ──────────────────────────────────────────────────────
    function showDrilldown(title, projects) {
        document.getElementById('dashboard-drilldown-title').textContent = title;
        const body = document.getElementById('dashboard-drilldown-body');
        body.innerHTML = '';
        if (!projects || projects.length === 0) {
            const p = document.createElement('p');
            p.className = 'text-muted mb-0';
            p.textContent = 'No projects found.';
            body.appendChild(p);
        } else {
            const ul = document.createElement('ul');
            ul.className = 'list-unstyled mb-0';
            projects.forEach(function (proj) {
                const li = document.createElement('li');
                li.className = 'mb-1';
                const a = document.createElement('a');
                a.href = proj.url;
                a.textContent = proj.name;
                a.className = 'text-decoration-none';
                li.appendChild(a);
                ul.appendChild(li);
            });
            body.appendChild(ul);
        }
        Modal.getOrCreateInstance(
            document.getElementById('dashboard-drilldown-modal')
        ).show();
    }

    // ── 1. Ownership donut ────────────────────────────────────────────────
    const yourProjects   = @json($yourProjectsList);
    const sharedProjects = @json($sharedProjectsList);
    const ownershipData = [{
        type:   'pie',
        hole:   0.55,
        labels: ['Your Projects', 'Shared With You'],
        values: [{{ $userProjectsCount }}, {{ $otherProjectsCount }}],
        marker: { colors: ['#0d6efd', '#6ea8fe'] },
        textinfo:    'value',
        hoverinfo:   'label+value+percent',
        domain:      { x: [0, 1], y: [0, 1] },
    }];
    Plotly.newPlot('chart-ownership', ownershipData, plotLayout({
        showlegend: true,
        legend: { orientation: 'h', x: 0.5, xanchor: 'center', y: -0.15, yanchor: 'top', font: { size: 10 } },
        margin: { t: 5, b: 45, l: 5, r: 5 },
    }), plotConfig).then(() => {
        Plotly.Plots.resize(document.getElementById('chart-ownership'));
    });
    document.getElementById('chart-ownership').on('plotly_click', function (data) {
        const label = data.points[0].label;
        if (label === 'Your Projects') {
            showDrilldown('Your Projects', yourProjects);
        } else {
            showDrilldown('Shared With You', sharedProjects);
        }
    });

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
        domain:      { x: [0, 1], y: [0.08, 0.92] },
    }];
    Plotly.newPlot('chart-health', healthData, plotLayout({
        showlegend: true,
        legend: { orientation: 'h', x: 0.5, xanchor: 'center', y: -0.15, yanchor: 'top', font: { size: 10 } },
        margin: { t: 17, b: 45, l: 5, r: 5 },
    }), plotConfig).then(() => {
        Plotly.Plots.resize(document.getElementById('chart-health'));
    });

    // ── 3. Top projects by file count (horizontal bar, log scale) ─────────
    @php
        $topProjects = collect($projects)
            ->sortByDesc('file_count')
            ->take(8)
            ->values();
        $barNames        = $topProjects->pluck('name')->map(fn($n) => strlen($n) > 20 ? substr($n, 0, 18).'…' : $n)->values()->toArray();
        $barValues       = $topProjects->pluck('file_count')->values()->toArray();
        $topProjectUrls  = $topProjects->map(fn($p) => route('projects.show', [$p->id]))->values()->toArray();
    @endphp
    const topProjectUrls = @json($topProjectUrls);
    const topData = [{
        type:        'bar',
        orientation: 'h',
        x: @json($barValues),
        y: @json($barNames),
        marker: { color: '#0d6efd' },
        hovertemplate: '%{y}: %{x:,} files<extra></extra>',
    }];
    Plotly.newPlot('chart-top-projects', topData, plotLayout({
        margin:     { t: 10, b: 30, l: 130, r: 20 },
        showlegend: false,
        xaxis: { type: 'log', title: { text: 'Files (log scale)', font: { size: 10 } }, tickfont: { size: 9 } },
        yaxis: { autorange: 'reversed' },
    }), plotConfig);
    document.getElementById('chart-top-projects').on('plotly_click', function (data) {
        window.location.href = topProjectUrls[data.points[0].pointIndex];
    });

    // ── 4. Project activity over the last 12 months ───────────────────────
    @php
        $activityMonthKeys   = [];
        $activityMonthLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $activityMonthKeys[]   = $m->format('Y-m');
            $activityMonthLabels[] = $m->format('M Y');
        }
        $updatesByMonth = collect($projects)
            ->groupBy(fn($p) => \Carbon\Carbon::parse($p->updated_at)->format('Y-m'))
            ->map->count();
        $activityValues = array_map(
            fn($k) => $updatesByMonth->get($k, 0),
            $activityMonthKeys
        );
        // Projects updated per month key → [{name, url}]
        $activityProjectsByMonth = collect($projects)
            ->groupBy(fn($p) => \Carbon\Carbon::parse($p->updated_at)->format('Y-m'))
            ->map(fn($group) => $group->map(fn($p) => [
                'name' => $p->name,
                'url'  => route('projects.show', [$p->id]),
            ])->values()->toArray())
            ->toArray();
    @endphp
    const activityMonthKeys      = @json($activityMonthKeys);
    const activityMonthLabels    = @json($activityMonthLabels);
    const activityProjectsByMonth = @json($activityProjectsByMonth);
    const activityData = [{
        type: 'bar',
        x:    activityMonthLabels,
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
        autosize: true,
    }), plotConfig).then(() => {
        Plotly.Plots.resize(document.getElementById('chart-activity'));
    });
    document.getElementById('chart-activity').on('plotly_click', function (data) {
        const idx      = data.points[0].pointIndex;
        const monthKey = activityMonthKeys[idx];
        const label    = activityMonthLabels[idx];
        const projects = activityProjectsByMonth[monthKey] || [];
        showDrilldown('Projects updated in ' + label, projects);
    });

    // ── 5. Storage treemap ────────────────────────────────────────────────
    @php
        $staleProjects = collect($projects)
            ->map(fn($p) => [
                'name' => strlen($p->name) > 20 ? substr($p->name, 0, 18).'…' : $p->name,
                'days' => \Carbon\Carbon::parse($p->updated_at)->diffInDays(now()),
                'url'  => route('projects.show', [$p->id]),
                'updated' => \Carbon\Carbon::parse($p->updated_at)->format('M j, Y'),
            ])
            ->sortByDesc('days')
            ->take(8)
            ->values();

        $staleNames = $staleProjects->pluck('name')->toArray();
        $staleDays = $staleProjects->pluck('days')->toArray();
        $staleUrls = $staleProjects->pluck('url')->toArray();
        $staleUpdated = $staleProjects->pluck('updated')->toArray();
    @endphp
    @if(count($staleProjects) > 0)
    const staleProjectUrls = @json($staleUrls);

    const staleData = [{
        type: 'bar',
        orientation: 'h',
        y: @json($staleNames),
        x: @json($staleDays),
        marker: {
            color: @json($staleDays),
            colorscale: [
                [0, '#cfe2ff'],
                [0.5, '#ffc107'],
                [1, '#dc3545']
            ],
            showscale: false,
        },
        customdata: @json($staleUpdated),
        hovertemplate: '%{y}<br>%{x} days since update<br>Last updated: %{customdata}<extra></extra>',
    }];

    Plotly.newPlot('chart-stale-projects', staleData, plotLayout({
        margin: { t: 5, b: 35, l: 115, r: 10 },
        showlegend: false,
        xaxis: {
            title: { text: 'Days stale', font: { size: 10 } },
            tickfont: { size: 9 },
        },
        yaxis: {
            autorange: 'reversed',
            tickfont: { size: 9 },
        },
    }), plotConfig).then(() => {
        Plotly.Plots.resize(document.getElementById('chart-stale-projects'));
    });

    document.getElementById('chart-stale-projects').on('plotly_click', function (data) {
        window.location.href = staleProjectUrls[data.points[0].pointIndex];
    });
    @else
    document.getElementById('chart-stale-projects').innerHTML =
        '<p class="text-muted text-center pt-4">No stale projects</p>';
    @endif

})();
</script>
@endpush
