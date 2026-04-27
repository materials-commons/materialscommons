@extends('layouts.app')

@section('pageTitle', $user->name . ' — Author Profile')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @php
        $analyticsKey    = 'mc_pub_author_' . $user->id . '_analytics';
        $ownedCount      = $profile->ownedDatasets->count();
        $includedCount   = $profile->includedDatasets->count();
        $paperCount      = $profile->papers->count();
        $coauthorCount   = count($profile->coAuthors);
        $activeThresh    = now()->subYears(2);
    @endphp

    {{-- ══ Profile header ══════════════════════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex gap-4 align-items-start">
                {{-- Avatar placeholder --}}
                <div
                    class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-light border"
                    style="width:80px; height:80px;">
                    <i class="fas fa-user fa-2x text-muted"></i>
                </div>

                {{-- Details --}}
                <div class="flex-grow-1">
                    <h4 class="mb-1">{{ $user->name }}</h4>

                    {{-- Meta row --}}
                    <div class="d-flex flex-wrap gap-3 mb-2">
                        @if(!blank($user->affiliations))
                            <span class="text-muted">
                            <i class="fas fa-building me-1" style="font-size:.8rem;"></i>{{ $user->affiliations }}
                        </span>
                        @endif
                        @if(!blank($user->orcid))
                            <a href="https://orcid.org/{{ $user->orcid }}" target="_blank"
                               class="text-decoration-none text-muted">
                                <i class="fas fa-id-badge me-1" style="font-size:.8rem;"></i>ORCID: {{ $user->orcid }}
                            </a>
                        @endif
                        @if(!blank($user->homepage_url))
                            <a href="{{ $user->homepage_url }}" target="_blank"
                               class="text-decoration-none text-muted">
                                <i class="fas fa-globe me-1" style="font-size:.8rem;"></i>Homepage
                            </a>
                        @endif
{{--                        <span class="text-muted">--}}
{{--                        <i class="fas fa-envelope me-1" style="font-size:.8rem;"></i>{{ $user->email }}--}}
                    </span>
                    </div>

                    @if(!blank($user->description))
                        <p class="text-muted mb-0" style="font-size:.9rem;">{{ $user->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ══ KPI strip ════════════════════════════════════════════════════════════════ --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Owned</div>
                <div class="fw-bold fs-5 text-primary">{{ number_format($ownedCount) }}</div>
                <div class="text-muted" style="font-size:.65rem;">datasets</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Included In</div>
                <div class="fw-bold fs-5 text-info">{{ number_format($includedCount) }}</div>
                <div class="text-muted" style="font-size:.65rem;">datasets</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Views</div>
                <div class="fw-bold fs-5 text-success">{{ number_format($profile->totalViews) }}</div>
                <div class="text-muted" style="font-size:.65rem;">on owned</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Downloads</div>
                <div class="fw-bold fs-5 text-warning">{{ number_format($profile->totalDownloads) }}</div>
                <div class="text-muted" style="font-size:.65rem;">on owned</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Papers</div>
                <div class="fw-bold fs-5 text-secondary">{{ number_format($paperCount) }}</div>
                <div class="text-muted" style="font-size:.65rem;">across datasets</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Co-authors</div>
                <div class="fw-bold fs-5 text-danger">{{ number_format($coauthorCount) }}</div>
                <div class="text-muted" style="font-size:.65rem;">unique</div>
            </div>
        </div>
    </div>

    {{-- ══ Analytics — collapsible ═════════════════════════════════════════════════ --}}
    @if($ownedCount + $includedCount > 0)
        <div class="d-flex align-items-center mb-2">
            <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                    type="button"
                    id="author-analytics-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#author-analytics"
                    aria-expanded="false"
                    aria-controls="author-analytics">
                <i class="fas fa-chevron-right fa-fw" id="author-analytics-chevron"
                   style="transition:transform .2s; font-size:.75rem;"></i>
                <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                Analytics
            </span>
            </button>
            <hr class="flex-grow-1 ms-3 my-0 opacity-25">
        </div>

        <div class="collapse mb-3" id="author-analytics">
            <div class="row g-3">

                {{-- Publication timeline --}}
                @if(count($profile->pubTimeline) > 1)
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-calendar-alt me-1"></i> Publication Timeline
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">Owned datasets published per
                                    month</p>
                                <div id="chart-author-timeline" style="height:200px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Top tags --}}
                @if(count($profile->chartTagNames) > 0)
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-tags me-1"></i> Top Tags
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">Across all datasets</p>
                                <div id="chart-author-tags"
                                     style="height:{{ min(60 + count($profile->chartTagNames) * 26, 380) }}px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Top co-authors --}}
                @if(count($profile->chartCoauthorNames) > 0)
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-0">
                                    <i class="fas fa-users me-1"></i> Frequent Co-authors
                                </h6>
                                <p class="text-muted mb-1" style="font-size:.7rem;">Shared datasets count</p>
                                <div id="chart-author-coauthors"
                                     style="height:{{ min(60 + count($profile->chartCoauthorNames) * 26, 380) }}px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif

    {{-- ══ Content tabs ════════════════════════════════════════════════════════════ --}}
    <ul class="nav nav-pills mb-3" id="author-tabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-owned" type="button">
                <i class="fas fa-database me-1"></i>Owned Datasets
                <span class="badge text-bg-primary ms-1">{{ $ownedCount }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-included" type="button">
                <i class="fas fa-list me-1"></i>Included In
                <span class="badge text-bg-info ms-1">{{ $includedCount }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-papers" type="button">
                <i class="fas fa-file-alt me-1"></i>Papers
                <span class="badge text-bg-secondary ms-1">{{ $paperCount }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-coauthors" type="button">
                <i class="fas fa-users me-1"></i>Co-authors
                <span class="badge text-bg-danger ms-1">{{ $coauthorCount }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-tags" type="button">
                <i class="fas fa-tags me-1"></i>Tags
                <span class="badge text-bg-success ms-1">{{ count($profile->allTags) }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ── Owned Datasets ────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade show active" id="tab-owned">
            @if($ownedCount === 0)
                <p class="text-muted">No published datasets owned by this author.</p>
            @else
                <table id="owned-datasets-table" class="table table-hover align-middle" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Published</th>
                        <th>PublishedTS</th>{{-- hidden sort proxy --}}
                        <th>Views</th>
                        <th>Downloads</th>
                        <th>Tags</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($profile->ownedDatasets as $ds)
                        <tr>
                            <td>
                                <a href="{{ route('public.datasets.show', $ds) }}" class="fw-semibold">
                                    {{ $ds->name }}
                                </a>
                            </td>
                            <td class="text-muted small">
                                {{ $ds->published_at ? $ds->published_at->format('M j, Y') : '—' }}
                            </td>
                            <td>{{ $ds->published_at ? $ds->published_at->timestamp : 0 }}</td>
                            <td>{{ number_format($ds->views_count) }}</td>
                            <td>{{ number_format($ds->downloads_count) }}</td>
                            <td>
                                @foreach($ds->tags as $tag)
                                    <a href="{{ route('public.tags.search', ['tag' => $tag->name]) }}"
                                       class="badge text-bg-success text-decoration-none me-1"
                                       style="font-size:.72rem;">{{ $tag->name }}</a>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- ── Included In ───────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade" id="tab-included">
            @if($includedCount === 0)
                <p class="text-muted">This author does not appear in other published datasets.</p>
            @else
                <table id="included-datasets-table" class="table table-hover align-middle" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Owner</th>
                        <th>Published</th>
                        <th>PublishedTS</th>{{-- hidden sort proxy --}}
                        <th>Tags</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($profile->includedDatasets as $ds)
                        <tr>
                            <td>
                                <a href="{{ route('public.datasets.show', $ds) }}" class="fw-semibold">
                                    {{ $ds->name }}
                                </a>
                            </td>
                            <td class="text-muted small">{{ $ds->owner->name ?? '—' }}</td>
                            <td class="text-muted small">
                                {{ $ds->published_at ? $ds->published_at->format('M j, Y') : '—' }}
                            </td>
                            <td>{{ $ds->published_at ? $ds->published_at->timestamp : 0 }}</td>
                            <td>
                                @foreach($ds->tags as $tag)
                                    <a href="{{ route('public.tags.search', ['tag' => $tag->name]) }}"
                                       class="badge text-bg-success text-decoration-none me-1"
                                       style="font-size:.72rem;">{{ $tag->name }}</a>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- ── Papers ─────────────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade" id="tab-papers">
            @if($paperCount === 0)
                <p class="text-muted">No papers associated with this author's datasets.</p>
            @else
                <table id="papers-table" class="table table-hover align-middle" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>DOI</th>
                        <th>Reference</th>
                        <th>Datasets</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($profile->papers as $item)
                        @php $paper = $item['paper']; $pDatasets = $item['datasets']; @endphp
                        <tr>
                            <td>
                                @if(!blank($paper->url))
                                    <a href="{{ $paper->url }}" target="_blank">{{ $paper->name }}</a>
                                @elseif(!blank($paper->doi))
                                    <a href="https://doi.org/{{ $paper->doi }}" target="_blank">{{ $paper->name }}</a>
                                @else
                                    {{ $paper->name }}
                                @endif
                            </td>
                            <td>
                                @if(!blank($paper->doi))
                                    <a href="https://doi.org/{{ $paper->doi }}" target="_blank"
                                       class="text-muted small text-decoration-none">
                                        <i class="fas fa-external-link-alt me-1"
                                           style="font-size:.7rem;"></i>{{ $paper->doi }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $paper->reference ?? '—' }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($pDatasets as $pDs)
                                        <a href="{{ route('public.datasets.show', $pDs) }}"
                                           classx="badge text-bg-light border text-dark text-decoration-none"
                                           stylex="font-size:.72rem; font-weight:normal;">
                                            <i class="fas fa-database me-1 text-muted"
                                               style="font-size:.65rem;"></i>{{ $pDs->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- ── Co-authors ─────────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade" id="tab-coauthors">
            @if($coauthorCount === 0)
                <p class="text-muted">No co-authors found.</p>
            @else
                @php $maxC = $profile->coAuthors ? max(array_column($profile->coAuthors, 'count')) : 1; @endphp
                <table id="coauthors-table" class="table table-hover align-middle" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th>Author</th>
                        <th>Count</th>
                        <th>Shared Datasets</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($profile->coAuthors as $name => $data)
                        <tr>
                            <td>
                                @if($data['user'])
                                    <a href="{{ route('public.authors.show', $data['user']) }}" class="fw-semibold">{{ $name }}</a>
                                    <span class="badge text-bg-light border text-muted ms-1" style="font-size:.65rem;">
                                        <i class="fas fa-check me-1 text-success"></i>MC
                                    </span>
                                @else
                                    {{ $name }}
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge text-bg-primary">{{ $data['count'] }}</span>
                                    <div class="flex-grow-1" style="max-width:80px;">
                                        <div style="height:6px; border-radius:3px; background:#dee2e6;">
                                            <div style="height:6px; border-radius:3px; background:#0d6efd;
                                                    width:{{ round($data['count']/$maxC*100) }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($data['datasets'] as $cDs)
                                        <a href="{{ route('public.datasets.show', $cDs) }}"
                                           class="badge text-bg-light border text-dark text-decoration-none"
                                           style="font-size:.72rem; font-weight:normal;">
                                            <i class="fas fa-database me-1 text-muted" style="font-size:.65rem;"></i>{{ $cDs->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- ── Tags ───────────────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade" id="tab-tags">
            @php
                $hasTags = count($profile->ownedTags) + count($profile->includedTags) > 0;
            @endphp
            @if(!$hasTags)
                <p class="text-muted">No tags found across this author's datasets.</p>
            @else
                <div class="row g-4">
                    @if(count($profile->ownedTags) > 0)
                        <div class="col-12 col-md-6">
                            <h6 class="fw-semibold text-muted text-uppercase mb-2"
                                style="font-size:.78rem; letter-spacing:.04em;">
                                <i class="fas fa-database me-1"></i>In My Datasets
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($profile->ownedTags as $tag => $count)
                                    <a href="{{ route('public.tags.search', ['tag' => $tag]) }}"
                                       class="badge text-bg-success text-decoration-none px-2 py-1"
                                       style="font-size:.82rem; font-weight:normal;">
                                        {{ $tag }}
                                        <span class="badge text-bg-light text-dark ms-1"
                                              style="font-size:.7rem;">{{ $count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(count($profile->includedTags) > 0)
                        <div class="col-12 col-md-6">
                            <h6 class="fw-semibold text-muted text-uppercase mb-2"
                                style="font-size:.78rem; letter-spacing:.04em;">
                                <i class="fas fa-list me-1"></i>In Datasets I'm Listed In
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($profile->includedTags as $tag => $count)
                                    <a href="{{ route('public.tags.search', ['tag' => $tag]) }}"
                                       class="badge text-bg-info text-decoration-none px-2 py-1"
                                       style="font-size:.82rem; font-weight:normal;">
                                        {{ $tag }}
                                        <span class="badge text-bg-light text-dark ms-1"
                                              style="font-size:.7rem;">{{ $count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

    </div>{{-- .tab-content --}}

    @push('scripts')
        <script>
            (function () {
                // ── Analytics collapse ────────────────────────────────────────────────
                const STORAGE_KEY = '{{ $analyticsKey }}';
                const panel = document.getElementById('author-analytics');
                const toggle = document.getElementById('author-analytics-toggle');
                const chevron = document.getElementById('author-analytics-chevron');

                if (panel) {
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
                }

                // ── Charts ────────────────────────────────────────────────────────────
                const plotConfig = {responsive: true, displayModeBar: false};
                const base = (extra) => Object.assign({
                    paper_bgcolor: 'transparent', plot_bgcolor: 'transparent',
                    font: {family: 'inherit', size: 11}, showlegend: false,
                }, extra);

                @if(count($profile->pubTimeline) > 1)
                Plotly.newPlot('chart-author-timeline', [{
                    type: 'bar',
                    x: @json(array_keys($profile->pubTimeline)),
                    y: @json(array_values($profile->pubTimeline)),
                    marker: {color: '#0d6efd'},
                    hovertemplate: '%{x}: %{y} dataset(s)<extra></extra>',
                }], base({
                    margin: {t: 10, b: 55, l: 35, r: 10},
                    xaxis: {tickangle: -45, tickfont: {size: 9}},
                    yaxis: {tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6'},
                }), plotConfig);
                @endif

                @if(count($profile->chartTagNames) > 0)
                Plotly.newPlot('chart-author-tags', [{
                    type: 'bar', orientation: 'h',
                    y: @json($profile->chartTagNames),
                    x: @json($profile->chartTagCounts),
                    marker: {color: '#198754'},
                    hovertemplate: '%{y}: %{x} dataset(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $profile->chartTagCounts)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 160, r: 20},
                    xaxis: {
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'datasets', font: {size: 10}}
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                @if(count($profile->chartCoauthorNames) > 0)
                Plotly.newPlot('chart-author-coauthors', [{
                    type: 'bar', orientation: 'h',
                    y: @json($profile->chartCoauthorNames),
                    x: @json($profile->chartCoauthorCounts),
                    marker: {color: '#dc3545'},
                    hovertemplate: '%{y}: %{x} shared dataset(s)<extra></extra>',
                    text: @json(array_map(fn($v) => (string)$v, $profile->chartCoauthorCounts)),
                    textposition: 'inside', insidetextanchor: 'end',
                    textfont: {color: 'white', size: 9},
                }], base({
                    margin: {t: 5, b: 30, l: 160, r: 20},
                    xaxis: {
                        tickformat: ',d', tickfont: {size: 9}, gridcolor: '#dee2e6',
                        title: {text: 'shared datasets', font: {size: 10}}
                    },
                    yaxis: {autorange: 'reversed', tickfont: {size: 10}},
                }), plotConfig);
                @endif

                // Resize charts when a tab becomes visible
                document.querySelectorAll('[data-bs-toggle="pill"]').forEach(btn => {
                    btn.addEventListener('shown.bs.tab', () => {
                        document.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                    });
                });

            })();
        </script>

        <script>
            $(document).ready(() => {
                // Owned datasets
                $('#owned-datasets-table').DataTable({
                    pageLength: 25,
                    stateSave: true,
                    order: [[2, 'desc']],
                    columnDefs: [
                        {targets: [2], visible: false},
                    ],
                });

                // Included datasets
                $('#included-datasets-table').DataTable({
                    pageLength: 25,
                    stateSave: true,
                    order: [[3, 'desc']],
                    columnDefs: [
                        {targets: [2], orderData: [3]},
                        {targets: [3], visible: false},
                    ],
                });

                // Papers
                $('#papers-table').DataTable({
                    pageLength: 25,
                    stateSave: true,
                    order: [[0, 'asc']],
                });

                // Co-authors — cols: 0:Author 1:Count(badge+bar) 2:Shared Datasets
                $('#coauthors-table').DataTable({
                    pageLength: 25,
                    stateSave:  false,
                    order:      [[1, 'desc']],
                    columnDefs: [
                        {targets: [2], orderable: false},
                    ],
                });
            });
        </script>
    @endpush
@stop
