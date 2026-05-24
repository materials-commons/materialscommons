@extends('layouts.app')

@section('pageTitle', 'Tag: ' . $tag . ' — ' . $community->name)

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @php
        $inCount      = $datasetsFromCommunity->count();
        $outCount     = $datasetsNotFromCommunity->count();
        $inViews      = $datasetsFromCommunity->sum('views_count');
        $inDownloads  = $datasetsFromCommunity->sum('downloads_count');

        // Top 10 by views / downloads (in-community only)
        $topViewed     = $datasetsFromCommunity->filter(fn($ds) => $ds->views_count > 0)
                                               ->sortByDesc('views_count')->take(10)->values();
        $topDownloaded = $datasetsFromCommunity->filter(fn($ds) => $ds->downloads_count > 0)
                                               ->sortByDesc('downloads_count')->take(10)->values();

        $viewNames  = $topViewed->map(fn($ds) => mb_strlen($ds->name) > 45 ? mb_substr($ds->name, 0, 43).'…' : $ds->name)->toArray();
        $viewCounts = $topViewed->pluck('views_count')->toArray();
        $viewUrls   = $topViewed->map(fn($ds) => route('public.datasets.show', $ds))->toArray();

        $dlNames  = $topDownloaded->map(fn($ds) => mb_strlen($ds->name) > 45 ? mb_substr($ds->name, 0, 43).'…' : $ds->name)->toArray();
        $dlCounts = $topDownloaded->pluck('downloads_count')->toArray();
        $dlUrls   = $topDownloaded->map(fn($ds) => route('public.datasets.show', $ds))->toArray();

        // Publication timeline + datasets per month (in-community)
        $timeline         = [];
        $timelineDatasets = [];
        foreach ($datasetsFromCommunity as $ds) {
            if ($ds->published_at) {
                $mk = $ds->published_at->format('Y-m');
                $timeline[$mk] = ($timeline[$mk] ?? 0) + 1;
                $timelineDatasets[$mk][] = ['name' => $ds->name, 'url' => route('public.datasets.show', $ds)];
            }
        }
        ksort($timeline);
        ksort($timelineDatasets);
        $timelineLabels = array_keys($timeline);

        $storageKey = 'mc_pub_comm_' . $community->id . '_tag_analytics';
        $showAnalytics = $inCount > 1 && ($inViews > 0 || $inDownloads > 0 || count($timeline) > 1);

        // Top contributors from in-community datasets (by owner)
        $ownerCounts = [];
        $ownerUsers  = collect();
        foreach ($datasetsFromCommunity as $ds) {
            if ($ds->owner) {
                $ownerCounts[$ds->owner->name] = ($ownerCounts[$ds->owner->name] ?? 0) + 1;
                if (!$ownerUsers->has($ds->owner->name)) {
                    $ownerUsers->put($ds->owner->name, $ds->owner);
                }
            }
        }
        arsort($ownerCounts);
        $topAuthors     = array_slice($ownerCounts, 0, 10, true);
        $topAuthorUsers = $ownerUsers;

        // Related tags (from in-community datasets, excluding current tag)
        $relTagCounts = [];
        foreach ($datasetsFromCommunity as $ds) {
            foreach ($ds->tags as $t) {
                if ($t->name !== $tag) {
                    $relTagCounts[$t->name] = ($relTagCounts[$t->name] ?? 0) + 1;
                }
            }
        }
        arsort($relTagCounts);
        $relatedTags = $relTagCounts;
    @endphp

    {{-- ══ Breadcrumb ══════════════════════════════════════════════════════════════ --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('public.communities.index') }}">Communities</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('public.communities.datasets.index', $community) }}">{{ $community->name }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tag: {{ $tag }}</li>
        </ol>
    </nav>

    {{-- ══ Header ═══════════════════════════════════════════════════════════════════ --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div
            class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10"
            style="width:52px; height:52px;">
            <i class="fas fa-tag fa-lg text-warning"></i>
        </div>
        <div>
            <h4 class="mb-0">
                <span class="badge text-bg-success" style="font-size:1rem;">{{ $tag }}</span>
                <span class="text-muted fw-normal fs-6 ms-2">in {{ $community->name }}</span>
            </h4>
            <div class="text-muted" style="font-size:.85rem;">
                {{ number_format($inCount) }} in-community · {{ number_format($outCount) }} other published
                dataset{{ $outCount !== 1 ? 's' : '' }}
            </div>
        </div>
    </div>

    {{-- ══ Top Contributors ════════════════════════════════════════════════════════ --}}
    @if(!empty($topAuthors))
        <div class="mb-3">
            <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
                <i class="fas fa-users me-1"></i>Top Contributors
            </div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($topAuthors as $authorName => $count)
                    @php $mcUser = $topAuthorUsers->get($authorName) ?? null; @endphp
                    @if($mcUser)
                        <a href="{{ route('public.authors.show', $mcUser) }}"
                           class="d-flex align-items-center gap-1 text-decoration-none badge text-bg-light border text-dark"
                           style="font-size:.8rem; font-weight:normal; padding:.35em .6em;">
                            {{ $authorName }}
                            <span class="badge text-bg-primary ms-1" style="font-size:.6rem;">MC</span>
                            <span class="text-muted ms-1">({{ $count }})</span>
                        </a>
                    @else
                        <span class="badge text-bg-light border text-dark"
                              style="font-size:.8rem; font-weight:normal; padding:.35em .6em;">
                            {{ $authorName }}
                            <span class="text-muted ms-1">({{ $count }})</span>
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- ══ Related Tags ════════════════════════════════════════════════════════════ --}}
    @if(!empty($relatedTags))
        <div class="mb-4">
            <div class="text-muted fw-semibold text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
                <i class="fas fa-tags me-1"></i>Related Tags
            </div>
            <div class="d-flex flex-wrap gap-1">
                @foreach(array_slice($relatedTags, 0, 25, true) as $relTag => $relCount)
                    <a href="{{ route('public.tags.search', ['tag' => $relTag]) }}"
                       class="badge text-bg-success text-decoration-none"
                       style="font-size:.78rem; font-weight:normal;">
                        {{ $relTag }}
                        <span class="ms-1 opacity-75">{{ $relCount }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ══ KPI strip ══════════════════════════════════════════════════════════════ --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">In Community</div>
                <div class="fw-bold fs-5 text-primary">{{ number_format($inCount) }}</div>
                <div class="text-muted" style="font-size:.65rem;">datasets</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Other Published</div>
                <div class="fw-bold fs-5 text-secondary">{{ number_format($outCount) }}</div>
                <div class="text-muted" style="font-size:.65rem;">datasets</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Views</div>
                <div class="fw-bold fs-5 text-info">{{ number_format($inViews) }}</div>
                <div class="text-muted" style="font-size:.65rem;">in-community</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Downloads</div>
                <div class="fw-bold fs-5 text-success">{{ number_format($inDownloads) }}</div>
                <div class="text-muted" style="font-size:.65rem;">in-community</div>
            </div>
        </div>
    </div>

    {{-- ══ Analytics — collapsible ════════════════════════════════════════════════ --}}
    @if($showAnalytics)
        <div class="d-flex align-items-center mb-2">
            <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                    type="button"
                    id="tag-comm-analytics-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#tag-comm-analytics"
                    aria-expanded="false"
                    aria-controls="tag-comm-analytics">
                <i class="fas fa-chevron-right fa-fw" id="tag-comm-analytics-chevron"
                   style="transition:transform .2s; font-size:.75rem;"></i>
                <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                    Analytics
                </span>
            </button>
            <hr class="flex-grow-1 ms-3 my-0 opacity-25">
        </div>

        <div class="collapse mb-3" id="tag-comm-analytics">
            <div class="row g-3">
                @if($inViews > 0)
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 background-white">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-eye me-1"></i> Top Viewed
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">In-community — click a bar to open
                                    the dataset</p>
                                <div id="chart-tag-comm-views"
                                     style="height:{{ min(60 + count($viewNames) * 28, 400) }}px; cursor:pointer;"></div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($inDownloads > 0)
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 background-white">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-download me-1"></i> Top Downloaded
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">In-community — click a bar to open
                                    the dataset</p>
                                <div id="chart-tag-comm-downloads"
                                     style="height:{{ min(60 + count($dlNames) * 28, 400) }}px; cursor:pointer;"></div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($timeline) > 1)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3 background-white">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-calendar-alt me-1"></i> Publication Timeline
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">
                                    In-community datasets published over time — click a bar to see them
                                </p>
                                <div id="chart-tag-comm-timeline" style="height:200px; cursor:pointer;"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Modal for timeline click --}}
        <div class="modal fade" id="tag-comm-timeline-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-nav">
                        <h5 class="modal-title help-color" id="tag-comm-timeline-modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="tag-comm-timeline-modal-body"
                         style="max-height:60vh; overflow-y:auto;"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ══ In-community datasets ════════════════════════════════════════════════════ --}}
    <div class="d-flex align-items-center mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.72rem; letter-spacing:.04em;">
            <i class="fas fa-database me-1"></i>In Community: {{ $community->name }}
        </span>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>

    <table id="tag-matches-in-community" class="table table-hover align-middle mb-4" style="width:100%">
        <thead class="table-light">
        <tr>
            <th>Dataset</th>
            <th>Author</th>
            <th>Views</th>
            <th>Downloads</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datasetsFromCommunity as $ds)
            <tr>
                <td>
                    <a href="{{ route('public.datasets.show', [$ds]) }}" class="fw-semibold">{{ $ds->name }}</a>
                </td>
                <td>
                    <a href="{{ route('public.authors.search', ['search' => $ds->owner->name]) }}"
                       class="text-muted text-decoration-none">{{ $ds->owner->name }}</a>
                </td>
                <td>{{ number_format($ds->views_count) }}</td>
                <td>{{ number_format($ds->downloads_count) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- ══ Other published datasets ════════════════════════════════════════════════ --}}
    <div class="d-flex align-items-center mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.72rem; letter-spacing:.04em;">
            <i class="fas fa-globe me-1"></i>Other Published Datasets with This Tag
        </span>
        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
    </div>

    <table id="tag-matches-not-in-community" class="table table-hover align-middle" style="width:100%">
        <thead class="table-light">
        <tr>
            <th>Dataset</th>
            <th>Author</th>
            <th>Views</th>
            <th>Downloads</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datasetsNotFromCommunity as $ds)
            <tr>
                <td>
                    <a href="{{ route('public.datasets.show', [$ds]) }}" class="fw-semibold">{{ $ds->name }}</a>
                </td>
                <td>
                    <a href="{{ route('public.authors.search', ['search' => $ds->owner->name]) }}"
                       class="text-muted text-decoration-none">{{ $ds->owner->name }}</a>
                </td>
                <td>{{ number_format($ds->views_count) }}</td>
                <td>{{ number_format($ds->downloads_count) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#tag-matches-in-community').DataTable({pageLength: 25, stateSave: true, order: [[2, 'desc']]});
            $('#tag-matches-not-in-community').DataTable({pageLength: 25, stateSave: true, order: [[2, 'desc']]});
        });

        @if($showAnalytics)
        (function () {
            const STORAGE_KEY = '{{ $storageKey }}';
            const panel = document.getElementById('tag-comm-analytics');
            const toggle = document.getElementById('tag-comm-analytics-toggle');
            const chevron = document.getElementById('tag-comm-analytics-chevron');

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

            @if($inViews > 0)
            const viewUrls = @json($viewUrls);
            Plotly.newPlot('chart-tag-comm-views', [{
                type: 'bar', orientation: 'h',
                y: @json($viewNames),
                x: @json($viewCounts),
                marker: {color: '#0dcaf0'},
                hovertemplate: '%{y}: %{x:,} views<extra></extra>',
                text: @json(array_map(fn($v) => number_format($v), $viewCounts)),
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
            document.getElementById('chart-tag-comm-views').on('plotly_click', function (data) {
                window.location.href = viewUrls[data.points[0].pointIndex];
            });
            @endif

            @if($inDownloads > 0)
            const dlUrls = @json($dlUrls);
            Plotly.newPlot('chart-tag-comm-downloads', [{
                type: 'bar', orientation: 'h',
                y: @json($dlNames),
                x: @json($dlCounts),
                marker: {color: '#198754'},
                hovertemplate: '%{y}: %{x:,} downloads<extra></extra>',
                text: @json(array_map(fn($v) => number_format($v), $dlCounts)),
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
            document.getElementById('chart-tag-comm-downloads').on('plotly_click', function (data) {
                window.location.href = dlUrls[data.points[0].pointIndex];
            });
            @endif

            @if(count($timeline) > 1)
            const timelineLabels = @json($timelineLabels);
            const timelineDatasets = @json($timelineDatasets);
            Plotly.newPlot('chart-tag-comm-timeline', [{
                type: 'bar',
                x: timelineLabels,
                y: @json(array_values($timeline)),
                marker: {color: '#6f42c1'},
                hovertemplate: '%{x}: %{y} dataset(s)<extra></extra>',
            }], base({
                margin: {t: 5, b: 40, l: 40, r: 20},
                xaxis: {tickfont: {size: 9}, tickangle: -30},
                yaxis: {
                    tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                    title: {text: 'datasets', font: {size: 10}}
                },
            }), plotConfig);
            document.getElementById('chart-tag-comm-timeline').on('plotly_click', function (data) {
                const month = timelineLabels[data.points[0].pointIndex];
                const datasets = timelineDatasets[month] || [];
                document.getElementById('tag-comm-timeline-modal-title').textContent = 'Datasets published in ' + month;
                const body = document.getElementById('tag-comm-timeline-modal-body');
                body.innerHTML = '';
                if (datasets.length === 0) {
                    const p = document.createElement('p');
                    p.className = 'text-muted';
                    p.textContent = 'No datasets found.';
                    body.appendChild(p);
                } else {
                    const ul = document.createElement('ul');
                    ul.className = 'list-unstyled mb-0';
                    datasets.forEach(function (d) {
                        const li = document.createElement('li');
                        li.className = 'mb-1';
                        const a = document.createElement('a');
                        a.href = d.url;
                        a.textContent = d.name;
                        a.className = 'text-decoration-none';
                        li.appendChild(a);
                        ul.appendChild(li);
                    });
                    body.appendChild(ul);
                }
                bootstrap.Modal.getOrCreateInstance(
                    document.getElementById('tag-comm-timeline-modal')
                ).show();
            });
            @endif
        })();
        @endif
    </script>
@endpush
