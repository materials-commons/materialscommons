@extends('layouts.app')

@section('pageTitle', 'Public Data Community')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
@php
    $totalCommunities = $communities->count();
    $totalDatasets    = $communities->sum('datasets_count');
    $maxDatasets      = $totalCommunities > 0 ? $communities->max('datasets_count') : 1;
    $avgDatasets      = $totalCommunities > 0 ? round($totalDatasets / $totalCommunities, 1) : 0;

    $topCommunity      = $communities->sortByDesc('datasets_count')->first();
    $topCommunityName  = $topCommunity?->name ?? '—';
    $topCommunityCount = $topCommunity?->datasets_count ?? 0;

    // Chart: datasets per community (top 20)
    $chartComms   = $communities->sortByDesc('datasets_count')->take(20);
    $chartNames   = $chartComms->map(fn($c) => mb_strlen($c->name) > 35 ? mb_substr($c->name, 0, 33).'…' : $c->name)->values()->toArray();
    $chartCounts  = $chartComms->pluck('datasets_count')->values()->toArray();

    // Chart: datasets per organizer (aggregated)
    $byOrganizer = [];
    $orgUserMap  = [];
    foreach ($communities as $c) {
        $org = $c->owner->name ?? 'Unknown';
        $byOrganizer[$org] = ($byOrganizer[$org] ?? 0) + $c->datasets_count;
        if ($c->owner && !isset($orgUserMap[$org])) {
            $orgUserMap[$org] = $c->owner;
        }
    }
    arsort($byOrganizer);
    $orgNames  = array_keys($byOrganizer);
    $orgCounts = array_values($byOrganizer);
    $orgUrls   = array_map(
        fn($name) => isset($orgUserMap[$name]) ? route('public.authors.show', $orgUserMap[$name]) : null,
        $orgNames
    );
@endphp

    <h3 class="text-center mb-3">Data Communities</h3>

    {{-- ══ Browse-by discovery strip ══════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <a href="{{ route('public.index') }}" class="text-decoration-none">
                <div class="card border-0 h-100 shadow-sm browse-card" style="transition:box-shadow .15s;">
                    <div class="card-body d-flex align-items-center gap-3 py-3 background-white">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                             style="width:52px; height:52px;">
                            <i class="fas fa-database fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 text-primary lh-1">{{ number_format($datasetCount) }}</div>
                            <div class="fw-semibold" style="font-size:.9rem;">All Datasets</div>
                            <div class="text-muted" style="font-size:.75rem;">Browse the full collection</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('public.authors.index') }}" class="text-decoration-none">
                <div class="card border-0 h-100 shadow-sm browse-card" style="transition:box-shadow .15s;">
                    <div class="card-body d-flex align-items-center gap-3 py-3 background-white">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10"
                             style="width:52px; height:52px;">
                            <i class="fas fa-users fa-lg text-success"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 text-success lh-1">
                                <i class="fas fa-arrow-right me-1" style="font-size:.8rem;"></i>Browse
                            </div>
                            <div class="fw-semibold" style="font-size:.9rem;">By Author</div>
                            <div class="text-muted" style="font-size:.75rem;">Profiles, datasets &amp; analytics</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('public.tags.index') }}" class="text-decoration-none">
                <div class="card border-0 h-100 shadow-sm browse-card" style="transition:box-shadow .15s;">
                    <div class="card-body d-flex align-items-center gap-3 py-3 background-white">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10"
                             style="width:52px; height:52px;">
                            <i class="fas fa-tags fa-lg text-warning"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 text-warning lh-1">{{ number_format($tagCount) }}</div>
                            <div class="fw-semibold" style="font-size:.9rem;">Browse by Tag</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ $tagCount }} research topics</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- ══ KPI strip ════════════════════════════════════════════════════════════════ --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Communities</div>
                <div class="fw-bold fs-5 text-primary">{{ number_format($totalCommunities) }}</div>
                <div class="text-muted" style="font-size:.65rem;">public</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Total Datasets</div>
                <div class="fw-bold fs-5 text-info">{{ number_format($totalDatasets) }}</div>
                <div class="text-muted" style="font-size:.65rem;">across communities</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Avg Datasets</div>
                <div class="fw-bold fs-5 text-success">{{ $avgDatasets }}</div>
                <div class="text-muted" style="font-size:.65rem;">per community</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Largest</div>
                <div class="fw-bold text-warning" style="font-size:.85rem; line-height:1.2;">
                    {{ mb_strlen($topCommunityName) > 20 ? mb_substr($topCommunityName, 0, 18).'…' : $topCommunityName }}
                </div>
                <div class="text-muted" style="font-size:.65rem;">{{ $topCommunityCount }} dataset(s)</div>
            </div>
        </div>
    </div>

    {{-- ══ Analytics — collapsible ═════════════════════════════════════════════════ --}}
    @if($totalCommunities > 0)
        <div class="d-flex align-items-center mb-2">
            <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                    type="button"
                    id="comm-analytics-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#comm-analytics"
                    aria-expanded="false"
                    aria-controls="comm-analytics">
                <i class="fas fa-chevron-right fa-fw" id="comm-analytics-chevron"
                   style="transition:transform .2s; font-size:.75rem;"></i>
                <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                    Analytics
                </span>
            </button>
            <hr class="flex-grow-1 ms-3 my-0 opacity-25">
        </div>

        <div class="collapse mb-3" id="comm-analytics">
            <div class="row g-3">

                {{-- Datasets per community --}}
                @if($totalCommunities > 1)
                    <div class="col-12 col-md-7">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 background-white">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-layer-group me-1"></i> Datasets per Community
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">Top {{ count($chartNames) }} communities by dataset count</p>
                                <div id="chart-comm-datasets"
                                     style="height:{{ min(60 + count($chartNames) * 28, 500) }}px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Datasets per organizer --}}
                @if(count($orgNames) > 1)
                    <div class="col-12 col-md-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 background-white">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-user-tie me-1"></i> Datasets by Organizer
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">Total datasets across organised communities</p>
                                <div id="chart-comm-organizers"
                                     style="height:{{ min(60 + count($orgNames) * 28, 400) }}px; cursor:pointer;"></div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif

    {{-- ══ Table ════════════════════════════════════════════════════════════════════ --}}
    @include('public.communities._communities_table')

    @push('styles')
        <style>
            .browse-card:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important; }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const STORAGE_KEY = 'mc_pub_communities_analytics';
                const panel   = document.getElementById('comm-analytics');
                const toggle  = document.getElementById('comm-analytics-toggle');
                const chevron = document.getElementById('comm-analytics-chevron');

                if (!panel) return;

                if (localStorage.getItem(STORAGE_KEY) === 'true') {
                    panel.classList.add('show');
                    if (chevron) chevron.style.transform = 'rotate(90deg)';
                    if (toggle)  toggle.setAttribute('aria-expanded', 'true');
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

                @if($totalCommunities > 1)
                const commUrls = @json($communities->sortByDesc('datasets_count')->take(20)->map(fn($c) => route('public.communities.datasets.index', $c))->values());
                Plotly.newPlot('chart-comm-datasets', [{
                    type: 'bar', orientation: 'h',
                    y: @json($chartNames),
                    x: @json($chartCounts),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{y}: %{x} dataset(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $chartCounts)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 200, r: 20},
                    xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                            title: {text: 'datasets', font: {size: 10}}},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                document.getElementById('chart-comm-datasets').on('plotly_click', function (data) {
                    window.location.href = commUrls[data.points[0].pointIndex];
                });
                @endif

                @if(count($orgNames) > 1)
                const orgUrls = @json($orgUrls);
                Plotly.newPlot('chart-comm-organizers', [{
                    type: 'bar', orientation: 'h',
                    y: @json($orgNames),
                    x: @json($orgCounts),
                    marker: {color: '#198754'},
                    hovertemplate: '%{y}: %{x} dataset(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $orgCounts)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 160, r: 20},
                    xaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                            title: {text: 'datasets', font: {size: 10}}},
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                document.getElementById('chart-comm-organizers').on('plotly_click', function (data) {
                    const url = orgUrls[data.points[0].pointIndex];
                    if (url) window.location.href = url;
                });
                @endif

            })();
        </script>
    @endpush
@stop
