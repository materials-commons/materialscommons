@props([
    'user',
    'profile',
])

@php

    $ownedDsJson = $profile->ownedDatasets->map(fn($ds) => [
        'name' => $ds->name,
        'url' => route('public.datasets.show', $ds),
        'pub_month' => $ds->published_at?->format('Y-m'),
        'tags' => $ds->tags->pluck('name')->toArray(),
    ]);

    $allDsJson = $profile->allDatasets->map(fn($ds) => [
        'name' => $ds->name,
        'url' => route('public.datasets.show', $ds),
        'tags' => $ds->tags->pluck('name')->toArray(),
    ]);

    $coAuthorDsJson = collect($profile->coAuthors)->mapWithKeys(fn($data, $name) => [
        $name => $data['datasets']->map(fn($ds) => [
            'name' => $ds->name,
            'url' => route('public.datasets.show', $ds),
        ])->values()->toArray(),
    ]);

    $topViewed = $profile->allDatasets
        ->filter(fn($ds) => $ds->views_count > 0)
        ->sortByDesc('views_count')
        ->take(15);

    $topViewedNames = $topViewed
        ->map(fn($ds) => mb_strlen($ds->name) > 32 ? mb_substr($ds->name, 0, 30) . '…' : $ds->name)
        ->values()
        ->toArray();

    $topViewedCounts = $topViewed->pluck('views_count')->values()->toArray();
    $topViewedUrls = $topViewed->map(fn($ds) => route('public.datasets.show', $ds))->values()->toArray();

    $topDownloaded = $profile->allDatasets
        ->filter(fn($ds) => $ds->downloads_count > 0)
        ->sortByDesc('downloads_count')
        ->take(15);

    $topDownloadedNames = $topDownloaded
        ->map(fn($ds) => mb_strlen($ds->name) > 32 ? mb_substr($ds->name, 0, 30) . '…' : $ds->name)
        ->values()
        ->toArray();

    $topDownloadedCounts = $topDownloaded->pluck('downloads_count')->values()->toArray();
    $topDownloadedUrls = $topDownloaded->map(fn($ds) => route('public.datasets.show', $ds))->values()->toArray();
@endphp

@push('scripts')
    <script>
        (function () {
            const plotConfig = {responsive: true, displayModeBar: false};
            const base = (extra) => Object.assign({
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: {family: 'inherit', size: 11},
                showlegend: false,
            }, extra);



            @if(count($profile->chartTagNames) > 0)
            Plotly.newPlot('chart-author-tags', [{
                type: 'bar',
                orientation: 'h',
                y: @json($profile->chartTagNames),
                x: @json($profile->chartTagCounts),
                marker: {color: '#198754'},
                hovertemplate: '%{y}: %{x} dataset(s)<extra></extra>',
                text: @json(array_map(fn($v) => (string) $v, $profile->chartTagCounts)),
                textposition: 'inside',
                insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 160, r: 20},
                xaxis: {
                    tickformat: ',d',
                    tickfont: {size: 9},
                    gridcolor: '#dee2e6',
                    title: {text: 'datasets', font: {size: 10}},
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            @endif

            @if(count($profile->chartCoauthorNames) > 0)
            Plotly.newPlot('chart-author-coauthors', [{
                type: 'bar',
                orientation: 'h',
                y: @json($profile->chartCoauthorNames),
                x: @json($profile->chartCoauthorCounts),
                marker: {color: '#dc3545'},
                hovertemplate: '%{y}: %{x} shared dataset(s)<extra></extra>',
                text: @json(array_map(fn($v) => (string) $v, $profile->chartCoauthorCounts)),
                textposition: 'inside',
                insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 160, r: 20},
                xaxis: {
                    tickformat: ',d',
                    tickfont: {size: 9},
                    gridcolor: '#dee2e6',
                    title: {text: 'shared datasets', font: {size: 10}},
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), plotConfig);
            @endif

            @if(count($topViewedNames) > 0)
            const topViewedUrls = @json($topViewedUrls);

            Plotly.newPlot('chart-author-top-viewed', [{
                type: 'bar',
                orientation: 'h',
                y: @json($topViewedNames),
                x: @json($topViewedCounts),
                marker: {color: '#0dcaf0'},
                hovertemplate: '%{y}: %{x} views<extra></extra>',
                text: @json(array_map(fn($v) => number_format($v), $topViewedCounts)),
                textposition: 'inside',
                insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 230, r: 20},
                xaxis: {
                    tickformat: ',d',
                    tickfont: {size: 9},
                    gridcolor: '#dee2e6',
                    title: {text: 'views', font: {size: 10}},
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), {...plotConfig, cursor: 'pointer'});

            document.getElementById('chart-author-top-viewed').on('plotly_click', function (data) {
                window.location.href = topViewedUrls[data.points[0].pointIndex];
            });
            @endif

            @if(count($topDownloadedNames) > 0)
            const topDownloadedUrls = @json($topDownloadedUrls);

            Plotly.newPlot('chart-author-top-downloaded', [{
                type: 'bar',
                orientation: 'h',
                y: @json($topDownloadedNames),
                x: @json($topDownloadedCounts),
                marker: {color: '#fd7e14'},
                hovertemplate: '%{y}: %{x} downloads<extra></extra>',
                text: @json(array_map(fn($v) => number_format($v), $topDownloadedCounts)),
                textposition: 'inside',
                insidetextanchor: 'end',
                textfont: {color: 'white', size: 9},
            }], base({
                margin: {t: 5, b: 30, l: 230, r: 20},
                xaxis: {
                    tickformat: ',d',
                    tickfont: {size: 9},
                    gridcolor: '#dee2e6',
                    title: {text: 'downloads', font: {size: 10}},
                },
                yaxis: {autorange: 'reversed', tickfont: {size: 10}},
            }), {...plotConfig, cursor: 'pointer'});

            document.getElementById('chart-author-top-downloaded').on('plotly_click', function (data) {
                window.location.href = topDownloadedUrls[data.points[0].pointIndex];
            });
            @endif

            const TAB_KEY = 'mc_pub_author_{{ $user->id }}_tab';

            document.querySelectorAll('[data-bs-toggle="pill"]').forEach(btn => {
                btn.addEventListener('shown.bs.tab', function () {
                    localStorage.setItem(TAB_KEY, this.getAttribute('data-bs-target'));
                    document.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                });
            });

            const ownedDsData = @json($ownedDsJson);
            const allDsData = @json($allDsJson);
            const coAuthorDsMap = @json($coAuthorDsJson);

            const drillModal = new Modal(document.getElementById('ds-drilldown-modal'));
            const drillTitle = document.getElementById('ds-drilldown-title');
            const drillBody = document.getElementById('ds-drilldown-body');

            function showDrilldown(title, datasets) {
                drillTitle.textContent = title;

                if (!datasets || datasets.length === 0) {
                    drillBody.innerHTML = '<p class="text-muted mb-0">No datasets found.</p>';
                } else {
                    drillBody.innerHTML = datasets.map(ds =>
                        `<div class="py-2 border-bottom">
                            <a href="${ds.url}" class="fw-semibold text-decoration-none">
                                <i class="fas fa-database me-2 text-muted" style="font-size:.8rem;"></i>${ds.name}
                            </a>
                        </div>`
                    ).join('');
                }

                drillModal.show();
            }

            @if(count($profile->pubTimeline) > 1)
            const timelineMonths = @json(array_keys($profile->pubTimeline));

            document.getElementById('chart-author-timeline').on('plotly_click', function (data) {
                const month = timelineMonths[data.points[0].pointIndex];
                const filtered = ownedDsData.filter(ds => ds.pub_month === month);

                showDrilldown('Datasets published in ' + month, filtered);
            });
            @endif

            @if(count($profile->chartTagNames) > 0)
            document.getElementById('chart-author-tags').on('plotly_click', function (data) {
                const tag = data.points[0].y;
                const filtered = allDsData.filter(ds => ds.tags.includes(tag));

                showDrilldown('Datasets tagged "' + tag + '"', filtered);
            });
            @endif

            @if(count($profile->chartCoauthorNames) > 0)
            document.getElementById('chart-author-coauthors').on('plotly_click', function (data) {
                const name = data.points[0].y;
                const datasets = coAuthorDsMap[name] || [];

                showDrilldown('Shared datasets with ' + name, datasets);
            });
            @endif
        })();
    </script>

    <script>
        $(document).ready(() => {
            $('#owned-datasets-table').DataTable({
                pageLength: 25,
                stateSave: true,
                order: [[2, 'desc']],
                columnDefs: [
                    {targets: [2], visible: false},
                ],
            });

            $('#included-datasets-table').DataTable({
                pageLength: 25,
                stateSave: true,
                order: [[3, 'desc']],
                columnDefs: [
                    {targets: [2], orderData: [3]},
                    {targets: [3], visible: false},
                ],
            });

            $('#papers-table').DataTable({
                pageLength: 25,
                stateSave: true,
                order: [[0, 'asc']],
            });

            $('#coauthors-table').DataTable({
                pageLength: 25,
                stateSave: false,
                order: [[1, 'desc']],
                columnDefs: [
                    {targets: [2], orderable: false},
                ],
            });

            const savedTab = localStorage.getItem('mc_pub_author_{{ $user->id }}_tab');

            if (savedTab) {
                const tabEl = document.querySelector('[data-bs-target="' + savedTab + '"]');

                if (tabEl) {
                    Tab.getOrCreateInstance(tabEl).show();
                }
            }
        });
    </script>
@endpush
