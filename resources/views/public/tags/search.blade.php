@extends('layouts.app')

@section('pageTitle', 'Tag: ' . $tag)

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @php
        $dsCount        = $datasets->count();
        $totalViews     = $datasets->sum('views_count');
        $totalDownloads = $datasets->sum('downloads_count');

        $topViewed     = $datasets->sortByDesc('views_count')->take(10)->values();
        $topDownloaded = $datasets->sortByDesc('downloads_count')->take(10)->values();

        $viewNames  = $topViewed->map(fn($ds) => mb_strlen($ds->name) > 45 ? mb_substr($ds->name, 0, 43).'…' : $ds->name)->values()->toArray();
        $viewCounts = $topViewed->pluck('views_count')->values()->toArray();
        $viewUrls   = $topViewed->map(fn($ds) => route('public.datasets.show', $ds))->values()->toArray();

        $dlNames  = $topDownloaded->map(fn($ds) => mb_strlen($ds->name) > 45 ? mb_substr($ds->name, 0, 43).'…' : $ds->name)->values()->toArray();
        $dlCounts = $topDownloaded->pluck('downloads_count')->values()->toArray();
        $dlUrls   = $topDownloaded->map(fn($ds) => route('public.datasets.show', $ds))->values()->toArray();
    @endphp

    {{-- ══ Breadcrumb ══════════════════════════════════════════════════════════════ --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('public.tags.index') }}">Tags</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $tag }}</li>
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
            </h4>
            <div class="text-muted" style="font-size:.85rem;">
                {{ number_format($dsCount) }} dataset{{ $dsCount !== 1 ? 's' : '' }}
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

    {{-- ══ Analytics — collapsible ════════════════════════════════════════════════ --}}
    @if($dsCount > 1 && ($totalViews > 0 || $totalDownloads > 0 || count($timeline) > 1))
        <div class="d-flex align-items-center mb-2">
            <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                    type="button"
                    id="tag-analytics-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#tag-analytics"
                    aria-expanded="false"
                    aria-controls="tag-analytics">
                <i class="fas fa-chevron-right fa-fw" id="tag-analytics-chevron"
                   style="transition:transform .2s; font-size:.75rem;"></i>
                <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                Analytics
            </span>
            </button>
            <hr class="flex-grow-1 ms-3 my-0 opacity-25">
        </div>

        <div class="collapse mb-3" id="tag-analytics">
            <div class="row g-3">
                @if($totalViews > 0)
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-eye me-1"></i> Top Viewed Datasets
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">Click a bar to open the dataset</p>
                                <div id="chart-tag-views"
                                     style="height:{{ min(60 + count($viewNames) * 28, 400) }}px;"></div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($totalDownloads > 0)
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-download me-1"></i> Top Downloaded Datasets
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">Click a bar to open the dataset</p>
                                <div id="chart-tag-downloads"
                                     style="height:{{ min(60 + count($dlNames) * 28, 400) }}px;"></div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($timeline) > 1)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-calendar-alt me-1"></i> Publication Timeline
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">
                                    Datasets published over time with this tag
                                </p>
                                <div id="chart-tag-timeline" style="height:200px;"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ══ Datasets table ══════════════════════════════════════════════════════════ --}}
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
            <th>Tags</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datasets as $dataset)
            <tr>
                <td>
                    <a href="{{ route('public.datasets.show', [$dataset]) }}" class="fw-semibold">
                        {{ $dataset->name }}
                    </a>
                </td>
                <td>{{ $dataset->views_count ?? 0 }}</td>
                <td>{{ $dataset->downloads_count ?? 0 }}</td>
                <td>{{ $dataset->published_at ? $dataset->published_at->format('Y-m-d') : '' }}</td>
                <td class="text-muted small" style="max-width:260px;">
                    {{ \Illuminate\Support\Str::limit($dataset->summary ?? '', 100) }}
                </td>
                <td class="text-muted small">
                    @if($dataset->ds_authors)
                        {{ collect($dataset->ds_authors)->pluck('name')->filter()->join(', ') }}
                    @endif
                </td>
                <td>
                    @foreach($dataset->tags as $dtag)
                        @if($dtag->name === $tag)
                            <span class="badge text-bg-warning me-1" style="font-size:.7rem; font-weight:normal;">
                            {{ $dtag->name }}
                        </span>
                        @else
                            <a href="{{ route('public.tags.search', ['tag' => $dtag->name]) }}"
                               class="badge text-bg-success text-decoration-none me-1"
                               style="font-size:.7rem; font-weight:normal;">
                                {{ $dtag->name }}
                            </a>
                        @endif
                    @endforeach
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

            @if($dsCount > 1 && ($totalViews > 0 || $totalDownloads > 0 || count($timeline) > 1))
            (function () {
                const STORAGE_KEY = 'mc_tag_analytics_' + @json($tag);
                const panel = document.getElementById('tag-analytics');
                const chevron = document.getElementById('tag-analytics-chevron');
                const toggle = document.getElementById('tag-analytics-toggle');

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
                Plotly.newPlot('chart-tag-views', [{
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
                document.getElementById('chart-tag-views').on('plotly_click', function (data) {
                    window.location.href = viewUrls[data.points[0].pointIndex];
                });
                @endif

                @if($totalDownloads > 0)
                const dlUrls = @json($dlUrls);
                Plotly.newPlot('chart-tag-downloads', [{
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
                document.getElementById('chart-tag-downloads').on('plotly_click', function (data) {
                    window.location.href = dlUrls[data.points[0].pointIndex];
                });
                @endif

                @if(count($timeline) > 1)
                Plotly.newPlot('chart-tag-timeline', [{
                    type: 'bar',
                    x: @json(array_keys($timeline)),
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
                @endif
            })();
            @endif
        </script>
    @endpush
@endsection
