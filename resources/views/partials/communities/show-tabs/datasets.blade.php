@php
    $dsCollection   = $community->publishedDatasets;
    $dsCount        = $dsCollection->count();
    $totalViews     = $dsCollection->sum('views_count');
    $totalDownloads = $dsCollection->sum('downloads_count');

    $topViewed     = $dsCollection->sortByDesc('views_count')->take(10)->values();
    $topDownloaded = $dsCollection->sortByDesc('downloads_count')->take(10)->values();

    $viewNames  = $topViewed->map(fn($ds) => mb_strlen($ds->name) > 45 ? mb_substr($ds->name, 0, 43).'…' : $ds->name)->values()->toArray();
    $viewCounts = $topViewed->pluck('views_count')->values()->toArray();
    $viewUrls   = $topViewed->map(fn($ds) => route($datasetRouteName, $ds))->values()->toArray();

    $dlNames  = $topDownloaded->map(fn($ds) => mb_strlen($ds->name) > 45 ? mb_substr($ds->name, 0, 43).'…' : $ds->name)->values()->toArray();
    $dlCounts = $topDownloaded->pluck('downloads_count')->values()->toArray();
    $dlUrls   = $topDownloaded->map(fn($ds) => route($datasetRouteName, $ds))->values()->toArray();

    // Palette for contributor avatars (cycles by index)
    $avatarColors = ['#0d6efd','#198754','#dc3545','#fd7e14','#6f42c1','#0dcaf0','#20c997','#d63384'];
@endphp

{{-- ══ Community summary card ══════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 background-white">
        <div class="d-flex align-items-start gap-3 mb-3">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                 style="width:52px; height:52px;">
                <i class="fas fa-layer-group fa-lg text-primary"></i>
            </div>
            <div class="flex-grow-1">
                <h4 class="mb-1">{{ $community->name }}</h4>
                <div class="text-muted" style="font-size:.9rem;">
                    <i class="fas fa-user-tie me-1"></i>
                    Organized by
                    <a href="{{ route('public.authors.show', [$community->owner]) }}" class="fw-semibold">
                        {{ $community->owner->name }}
                    </a>
                    @if(!blank($community->owner->affiliations))
                        <span class="text-muted ms-1">({{ $community->owner->affiliations }})</span>
                    @endif
                    @auth
                        <a href="mailto:{{ $community->owner->email }}?subject={{ $community->name }}"
                           class="ms-2 text-muted" title="Email organizer">
                            <i class="fas fa-envelope"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        @if(!blank($community->summary))
            <p class="text-muted mb-3" style="font-size:.95rem; line-height:1.6;">{{ $community->summary }}</p>
        @endif

        @if(!blank($community->description) && $community->description !== $community->summary)
            <p class="text-muted mb-3" style="font-size:.9rem; line-height:1.6;">{{ $community->description }}</p>
        @endif

        {{-- Tags --}}
        @if(!empty($tags))
            <div class="mb-3">
                <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
                    <i class="fas fa-tags me-1"></i>Topics
                </div>
                <div class="d-flex flex-wrap gap-1">
                    @foreach($tags as $tag => $count)
                        <a href="{{ route('public.communities.search.tag', [$community, 'tag' => $tag]) }}"
                           class="badge text-bg-success text-decoration-none"
                           style="font-size:.78rem; font-weight:normal;">
                            {{ $tag }}
                            <span class="ms-1 opacity-75">{{ $count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Contributors --}}
        @if(!empty($contributors))
            <div>
                <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
                    <i class="fas fa-users me-1"></i>Contributors
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($contributors as $name => $affiliation)
                        @php
                            $initials   = collect(explode(' ', trim($name)))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->join('');
                            $color      = $avatarColors[$loop->index % count($avatarColors)];
                            $mcUser     = $contributorUsers->get($name) ?? null;
                            $profileUrl = $mcUser
                                ? route('public.authors.show', $mcUser)
                                : route('public.communities.search.author', [$community, 'author' => $name]);
                        @endphp
                        <div class="d-flex align-items-center gap-2 border rounded px-2 py-1 bg-light"
                             style="min-width:160px; max-width:240px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-bold"
                                 style="width:32px; height:32px; font-size:.7rem; background:{{ $color }};">
                                {{ $initials }}
                            </div>
                            <div class="overflow-hidden">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ $profileUrl }}"
                                       class="fw-semibold text-decoration-none text-truncate"
                                       style="font-size:.85rem;" title="{{ $name }}">
                                        {{ $name }}
                                    </a>
                                    @if($mcUser)
                                        <span class="badge text-bg-primary flex-shrink-0"
                                              style="font-size:.6rem; padding:.15em .35em;">MC</span>
                                    @endif
                                </div>
                                @if(!blank($affiliation))
                                    <div class="text-muted text-truncate" style="font-size:.73rem;"
                                         title="{{ $affiliation }}">
                                        {{ $affiliation }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

{{-- ══ KPI strip ══════════════════════════════════════════════════════════════════ --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-sm-4">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Datasets</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($dsCount) }}</div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Total Views</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($totalViews) }}</div>
        </div>
    </div>
    <div class="col-6 col-sm-4">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Total Downloads</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($totalDownloads) }}</div>
        </div>
    </div>
</div>

{{-- ══ Analytics — collapsible ═════════════════════════════════════════════════ --}}
@if($dsCount > 1 && ($totalViews > 0 || $totalDownloads > 0))
    <div class="d-flex align-items-center mb-2">
        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                type="button"
                id="comm-ds-analytics-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#comm-ds-analytics"
                aria-expanded="false"
                aria-controls="comm-ds-analytics">
            <i class="fas fa-chevron-right fa-fw" id="comm-ds-analytics-chevron"
               style="transition:transform .2s; font-size:.75rem;"></i>
            <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                Analytics
            </span>
        </button>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>

    <div class="collapse mb-3" id="comm-ds-analytics">
        <div class="row g-3">
            @if($totalViews > 0)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-eye me-1"></i> Top Viewed Datasets
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">Click a bar to open the dataset</p>
                            <div id="chart-comm-views"
                                 style="height:{{ min(60 + count($viewNames) * 28, 400) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif
            @if($totalDownloads > 0)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 background-white">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-download me-1"></i> Top Downloaded Datasets
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">Click a bar to open the dataset</p>
                            <div id="chart-comm-downloads"
                                 style="height:{{ min(60 + count($dlNames) * 28, 400) }}px;"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif

{{-- ══ Datasets Table ═══════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center mb-2">
    <span class="fw-semibold text-uppercase text-muted" style="font-size:.72rem; letter-spacing:.04em;">
        <i class="fas fa-database me-1"></i>Datasets
    </span>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<table id="datasets" class="table table-hover align-middle" style="width:100%">
    <thead class="table-light">
    <tr>
        <th>Name</th>
        <th>Views</th>
        <th>Downloads</th>
        <th>Published</th>
        <th>Summary</th>
        <th>Authors</th>
    </tr>
    </thead>
    <tbody>
    @foreach($community->publishedDatasets as $dataset)
        <tr>
            <td>
                <a href="{{ route($datasetRouteName, [$dataset]) }}" class="fw-semibold">
                    {{ $dataset->name }}
                </a>
            </td>
            <td>{{ $dataset->views_count ?? 0 }}</td>
            <td>{{ $dataset->downloads_count ?? 0 }}</td>
            <td>{{ $dataset->published_at ? $dataset->published_at->format('Y-m-d') : '' }}</td>
            <td class="text-muted small" style="max-width:280px;">
                {{ \Illuminate\Support\Str::limit($dataset->summary ?? '', 100) }}
            </td>
            <td class="text-muted small">
                @if($dataset->ds_authors)
                    {{ collect($dataset->ds_authors)->pluck('name')->filter()->join(', ') }}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#datasets').DataTable({
                pageLength: 25,
                stateSave: false,
                order: [[3, 'desc']],
                columnDefs: [
                    {targets: [1, 2], type: 'num'},
                ],
            });
        });

        @if($dsCount > 1 && ($totalViews > 0 || $totalDownloads > 0))
        (function () {
            const STORAGE_KEY = 'mc_comm_ds_analytics_{{ $community->id }}';
            const panel = document.getElementById('comm-ds-analytics');
            const chevron = document.getElementById('comm-ds-analytics-chevron');
            const toggle = document.getElementById('comm-ds-analytics-toggle');

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
                paper_bgcolor: 'transparent', plot_bgcolor: 'transparent',
                font: {family: 'inherit', size: 11}, showlegend: false,
            }, extra);

            @if($totalViews > 0)
            const viewUrls = @json($viewUrls);
            Plotly.newPlot('chart-comm-views', [{
                type: 'bar', orientation: 'h',
                y: @json($viewNames),
                x: @json($viewCounts),
                marker: {color: '#0dcaf0'},
                hovertemplate: '%{y}: %{x} views<extra></extra>',
                text: @json(array_map(fn($v) => (string)$v, $viewCounts)),
                textposition: 'inside', insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 200, r: 20},
                xaxis: {
                    tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                    title: {text: 'views', font: {size: 10}}
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            document.getElementById('chart-comm-views').on('plotly_click', function (data) {
                window.location.href = viewUrls[data.points[0].pointIndex];
            });
            @endif

            @if($totalDownloads > 0)
            const dlUrls = @json($dlUrls);
            Plotly.newPlot('chart-comm-downloads', [{
                type: 'bar', orientation: 'h',
                y: @json($dlNames),
                x: @json($dlCounts),
                marker: {color: '#198754'},
                hovertemplate: '%{y}: %{x} downloads<extra></extra>',
                text: @json(array_map(fn($v) => (string)$v, $dlCounts)),
                textposition: 'inside', insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 200, r: 20},
                xaxis: {
                    tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                    title: {text: 'downloads', font: {size: 10}}
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            document.getElementById('chart-comm-downloads').on('plotly_click', function (data) {
                window.location.href = dlUrls[data.points[0].pointIndex];
            });
            @endif
        })();
        @endif
    </script>
@endpush
