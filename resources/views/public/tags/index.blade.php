@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @php
        $counts      = array_column($tags, 'count');
        $totalTags   = count($tags);
        $totalUsages = array_sum($counts);
        $maxCount    = $totalTags > 0 ? max($counts) : 1;
        $avgUsage    = $totalTags > 0 ? round($totalUsages / $totalTags, 1) : 0;
        $activeThresh = now()->subYears(2);

        // Most used tag
        $sortedByCount = $tags;
        uasort($sortedByCount, fn($a, $b) => $b['count'] <=> $a['count']);
        $topTagName  = array_key_first($sortedByCount);
        $topTagCount = $sortedByCount[$topTagName]['count'] ?? 0;

        // Top 20 for chart
        $chartTags   = array_slice($sortedByCount, 0, 20, true);
        $chartNames  = array_map(fn($n) => mb_strlen($n) > 40 ? mb_substr($n, 0, 38) . '…' : $n, array_keys($chartTags));
        $chartCounts = array_column(array_values($chartTags), 'count');

        // Distribution
        $distrib = [];
        foreach ($counts as $c) {
            $distrib[$c] = ($distrib[$c] ?? 0) + 1;
        }
        ksort($distrib);
        $distribLabels = array_map(fn($n) => $n === 1 ? '1 dataset' : "$n datasets", array_keys($distrib));
        $distribValues = array_values($distrib);
    @endphp

    <h3 class="text-center">Published Dataset Tags</h3>
    <br/>

    {{-- ══ KPI strip ════════════════════════════════════════════════════════════════ --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Unique Tags</div>
                <div class="fw-bold fs-5 text-primary">{{ number_format($totalTags) }}</div>
                <div class="text-muted" style="font-size:.65rem;">distinct tags</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Total Usages</div>
                <div class="fw-bold fs-5 text-info">{{ number_format($totalUsages) }}</div>
                <div class="text-muted" style="font-size:.65rem;">tag–dataset pairs</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Avg Usage</div>
                <div class="fw-bold fs-5 text-success">{{ $avgUsage }}</div>
                <div class="text-muted" style="font-size:.65rem;">datasets per tag</div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Most Used</div>
                <div class="fw-bold text-warning" style="font-size:.85rem; line-height:1.2;">
                    {{ mb_strlen($topTagName) > 20 ? mb_substr($topTagName, 0, 18) . '…' : $topTagName }}
                </div>
                <div class="text-muted" style="font-size:.65rem;">{{ $topTagCount }} dataset(s)</div>
            </div>
        </div>
    </div>

    {{-- ══ Analytics — collapsible, default CLOSED ════════════════════════════════ --}}
    @if($totalTags > 0)
        <div class="d-flex align-items-center mb-2">
            <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                    type="button"
                    id="tags-analytics-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#tags-analytics"
                    aria-expanded="false"
                    aria-controls="tags-analytics">
                <i class="fas fa-chevron-right fa-fw" id="tags-analytics-chevron"
                   style="transition:transform .2s; font-size:.75rem;"></i>
                <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                    Analytics
                </span>
            </button>
            <hr class="flex-grow-1 ms-3 my-0 opacity-25">
        </div>

        <div class="collapse mb-3" id="tags-analytics">
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-tag me-1"></i> Most Used Tags
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                Top {{ count($chartNames) }} tags by number of datasets
                            </p>
                            <div id="chart-tags-top"
                                 style="height:{{ min(60 + count($chartNames) * 28, 500) }}px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title text-muted mb-0">
                                <i class="fas fa-chart-bar me-1"></i> Usage Distribution
                            </h6>
                            <p class="text-muted mb-1" style="font-size:.7rem;">
                                How many tags appear on 1, 2, 3… datasets
                            </p>
                            <div id="chart-tags-distrib" style="height:220px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ══ Table ════════════════════════════════════════════════════════════════════ --}}
    <table id="tags" class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th style="width:3rem;">#</th>
            <th>Tag</th>
            <th>Datasets</th>
            <th>Count</th>{{-- hidden, sort proxy for Datasets --}}
            <th>Most Recent</th>
            <th>RecentTS</th>{{-- hidden, sort proxy for Most Recent --}}
            <th style="width:5rem;"></th>{{-- Active badge --}}
        </tr>
        </thead>
        <tbody>
        @foreach($tags as $tag => $data)
            @php
                $count    = $data['count'];
                $latest   = $data['latest'];
                $since    = $data['since'];
                $barWidth = round($count / $maxCount * 100);
                $isActive = $latest && $latest >= $activeThresh;
            @endphp
            <tr>
                <td class="text-muted small rank-cell"></td>
                <td>
                    <a class="badge text-bg-success td-none text-white text-decoration-none"
                       href="{{ route('public.tags.search', ['tag' => $tag]) }}">{{ $tag }}</a>
                    @if($since)
                        <div class="text-muted" style="font-size:.7rem;">since {{ $since->format('Y') }}</div>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge text-bg-primary">{{ $count }}</span>
                        <div class="flex-grow-1" style="max-width:120px;">
                            <div style="height:6px; border-radius:3px; background:#dee2e6;">
                                <div
                                    style="height:6px; border-radius:3px; background:#0d6efd; width:{{ $barWidth }}%;"></div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>{{ $count }}</td>{{-- hidden, sort proxy --}}
                <td class="text-muted small">{{ $latest ? $latest->format('M j, Y') : '—' }}</td>
                <td>{{ $latest ? $latest->timestamp : 0 }}</td>{{-- hidden, sort proxy --}}
                <td>
                    @if($isActive)
                        <span class="badge text-bg-success" style="font-size:.7rem;">Active</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            (function () {
                const STORAGE_KEY = 'mc_pub_tags_analytics';
                const panel = document.getElementById('tags-analytics');
                const toggle = document.getElementById('tags-analytics-toggle');
                const chevron = document.getElementById('tags-analytics-chevron');

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

                Plotly.newPlot('chart-tags-top', [{
                    type: 'bar', orientation: 'h',
                    y:    @json($chartNames),
                    x:    @json($chartCounts),
                    marker: {color: '#198754'},
                    hovertemplate: '%{y}: %{x} dataset(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $chartCounts)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 180, r: 20},
                    xaxis: {
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'datasets', font: {size: 10}}
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);

                Plotly.newPlot('chart-tags-distrib', [{
                    type: 'bar',
                    x:    @json($distribLabels),
                    y:    @json($distribValues),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{x}: %{y} tag(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $distribValues)),
                    textposition: 'outside', textfont: {size: 9},
                }], base({
                    margin: {t: 20, b: 55, l: 40, r: 15},
                    xaxis: {tickangle: -30, tickfont: {size: 9}},
                    yaxis: {
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'tags', font: {size: 10}}
                    },
                }), plotConfig);
            })();
        </script>

        <script>
            $(document).ready(() => {
                $('#tags').DataTable({
                    pageLength: 100,
                    stateSave: false,
                    order: [[3, 'desc']],
                    columnDefs: [
                        {targets: [0], orderable: false, searchable: false},
                        {targets: [2], orderData: [3]},
                        {targets: [3], visible: false},
                        {targets: [4], orderData: [5]},
                        {targets: [5], visible: false},
                        {targets: [6], orderable: false, searchable: false},
                    ],
                    drawCallback: function () {
                        const api   = this.api();
                        const start = api.page.info().start;
                        api.rows({page: 'current'}).every(function (rowIdx) {
                            $(this.node()).find('.rank-cell').text(start + rowIdx + 1);
                        });
                    },
                });
            });
        </script>
    @endpush
@stop
