{{--
  Prototype v2 Published Datasets tab.
  To try it: in index.blade.php change
      @include('app.dashboard.tabs.published-datasets')
  to
      @include('app.dashboard.tabs.published-datasets-v2')

  Adds three charts above the existing DataTable:
    1. Views vs. Downloads grouped bar per dataset
    2. Cumulative engagement line chart over publication dates
    3. Publication timeline — one bar per calendar month
  All charts use Plotly (already loaded in app.blade.php).
  The DataTable below is unchanged from the original.
--}}

{{-- ─── Charts section ────────────────────────────────────────────────────── --}}
@if(count($publishedDatasets) > 0)
<div class="row g-3 mb-4">

    {{-- Chart 1: Views vs Downloads per dataset --}}
    <div class="col-12 col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-chart-bar me-1"></i> Views &amp; Downloads per Dataset
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Grouped by dataset — hover for exact counts
                </p>
                <div id="chart-ds-engagement" style="height:240px;"></div>
            </div>
        </div>
    </div>

    {{-- Chart 2: Publication timeline (datasets published per month) --}}
    <div class="col-12 col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-calendar-alt me-1"></i> Publication Timeline
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Datasets published per month
                </p>
                <div id="chart-ds-timeline" style="height:240px;"></div>
            </div>
        </div>
    </div>

</div>

{{-- Row 2: cumulative engagement line ─────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-chart-line me-1"></i> Cumulative Engagement Over Time
                </h6>
                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Running total of views and downloads sorted by publication date
                </p>
                <div id="chart-ds-cumulative" style="height:200px;"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const plotConfig = { responsive: true, displayModeBar: false };
    const baseLayout = (extra) => Object.assign({
        margin:          { t: 10, b: 10, l: 45, r: 10 },
        paper_bgcolor:   'transparent',
        plot_bgcolor:    'transparent',
        font:            { family: 'inherit', size: 11 },
        legend:          { orientation: 'h', y: -0.2, font: { size: 10 } },
    }, extra);

    // ── PHP data → JS ─────────────────────────────────────────────────────
    @php
        // Sort datasets by published_at ascending for timeline charts
        $dsSorted = collect($publishedDatasets)
            ->sortBy('published_at')
            ->values();

        // Truncate long names for axis labels
        $dsNames = $dsSorted
            ->map(fn($d) => strlen($d->name) > 22 ? substr($d->name, 0, 20).'…' : $d->name)
            ->values()->toArray();

        $dsViews     = $dsSorted->pluck('views_count')->values()->toArray();
        $dsDownloads = $dsSorted->pluck('downloads_count')->values()->toArray();
        $dsComments  = $dsSorted->pluck('comments_count')->values()->toArray();

        // Cumulative sums
        $cumViews = [];
        $cumDownloads = [];
        $runV = 0; $runD = 0;
        foreach ($dsSorted as $ds) {
            $runV += $ds->views_count;
            $runD += $ds->downloads_count;
            $cumViews[]     = $runV;
            $cumDownloads[] = $runD;
        }

        // Publication date labels (for cumulative x-axis)
        $dsPublishedLabels = $dsSorted
            ->map(fn($d) => $d->published_at->format('M j, Y'))
            ->values()->toArray();

        // Timeline: bucket by "published_at" year-month
        $timelineMonthKeys   = [];
        $timelineMonthLabels = [];
        // span from oldest to now
        $oldest = $dsSorted->first()?->published_at ?? now();
        $months = (int) $oldest->diffInMonths(now()) + 1;
        $months = min($months, 36); // cap at 3 years
        for ($i = $months - 1; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $timelineMonthKeys[]   = $m->format('Y-m');
            $timelineMonthLabels[] = $m->format('M Y');
        }
        $pubByMonth = $dsSorted->groupBy(fn($d) => $d->published_at->format('Y-m'))->map->count();
        $timelineValues = array_map(fn($k) => $pubByMonth->get($k, 0), $timelineMonthKeys);
    @endphp

    // ── 1. Views & Downloads grouped bar ─────────────────────────────────
    const engagementData = [
        {
            type:             'bar',
            name:             'Views',
            x:                @json($dsNames),
            y:                @json($dsViews),
            marker:           { color: '#0d6efd' },
            hovertemplate:    '%{x}<br>Views: %{y:,}<extra></extra>',
        },
        {
            type:             'bar',
            name:             'Downloads',
            x:                @json($dsNames),
            y:                @json($dsDownloads),
            marker:           { color: '#198754' },
            hovertemplate:    '%{x}<br>Downloads: %{y:,}<extra></extra>',
        },
        {
            type:             'bar',
            name:             'Comments',
            x:                @json($dsNames),
            y:                @json($dsComments),
            marker:           { color: '#fd7e14' },
            hovertemplate:    '%{x}<br>Comments: %{y:,}<extra></extra>',
        },
    ];
    Plotly.newPlot('chart-ds-engagement', engagementData, baseLayout({
        barmode:    'group',
        margin:     { t: 10, b: 80, l: 45, r: 10 },
        xaxis:      { tickangle: -30, tickfont: { size: 9 } },
        yaxis:      { tickformat: ',d' },
    }), plotConfig);

    // ── 2. Publication timeline bar ───────────────────────────────────────
    const timelineData = [{
        type:             'bar',
        x:                @json($timelineMonthLabels),
        y:                @json($timelineValues),
        marker:           { color: '#6ea8fe' },
        hovertemplate:    '%{x}: %{y} dataset(s) published<extra></extra>',
    }];
    Plotly.newPlot('chart-ds-timeline', timelineData, baseLayout({
        margin:     { t: 10, b: 70, l: 35, r: 10 },
        showlegend: false,
        xaxis:      { tickangle: -35, tickfont: { size: 9 } },
        yaxis:      { tickformat: 'd', dtick: 1 },
    }), plotConfig);

    // ── 3. Cumulative engagement line ─────────────────────────────────────
    const cumulativeData = [
        {
            type:          'scatter',
            mode:          'lines+markers',
            name:          'Cumulative Views',
            x:             @json($dsPublishedLabels),
            y:             @json($cumViews),
            line:          { color: '#0d6efd', width: 2 },
            marker:        { size: 5 },
            hovertemplate: '%{x}<br>Total views: %{y:,}<extra></extra>',
        },
        {
            type:          'scatter',
            mode:          'lines+markers',
            name:          'Cumulative Downloads',
            x:             @json($dsPublishedLabels),
            y:             @json($cumDownloads),
            line:          { color: '#198754', width: 2 },
            marker:        { size: 5 },
            hovertemplate: '%{x}<br>Total downloads: %{y:,}<extra></extra>',
        },
    ];
    Plotly.newPlot('chart-ds-cumulative', cumulativeData, baseLayout({
        margin:  { t: 10, b: 50, l: 55, r: 10 },
        xaxis:   { tickangle: -20, tickfont: { size: 9 } },
        yaxis:   { tickformat: ',d' },
    }), plotConfig);
})();
</script>
@endpush

@else
{{-- No datasets yet — show an encouraging empty state instead of blank charts --}}
<div class="text-center py-5 mb-4">
    <i class="fas fa-database text-muted mb-3" style="font-size:3rem; opacity:.4;"></i>
    <h5 class="text-muted">No published datasets yet</h5>
    <p class="text-muted mb-4">Once you publish a dataset its views, downloads, and publication history will appear here as charts.</p>
    <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
       class="btn btn-success">
        <i class="fas fa-plus me-2"></i> Publish Your First Dataset
    </a>
</div>
@endif

{{-- ─── Existing DataTable (unchanged) ────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Published Datasets</h5>
    <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
       title="Publish data" class="btn btn-success btn-sm">
        <i class="fas fa-plus me-2"></i> Create Dataset
    </a>
</div>

<table id="datasets" class="table table-hover" style="width:100%"
       aria-label="Published datasets">
    <thead>
    <tr>
        <th>Dataset</th>
        <th>Summary</th>
        <th>Published</th>
        <th>Date</th>
        <th>Views</th>
        <th>Downloads</th>
        <th>Comments</th>
    </tr>
    </thead>
    <tbody>
    @foreach($publishedDatasets as $ds)
        <tr>
            <td>
                <a href="{{route('projects.datasets.show', [$ds->project_id, $ds])}}">
                    {{$ds->name}}
                </a>
            </td>
            <td>{{$ds->summary}}</td>
            <td>{{$ds->published_at->diffForHumans()}}</td>
            <td>{{$ds->published_at}}</td>
            <td>{{$ds->views_count}}</td>
            <td>{{$ds->downloads_count}}</td>
            <td>{{$ds->comments_count}}</td>
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
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
