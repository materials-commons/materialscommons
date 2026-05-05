@props([
    'user',
    'profile',
    'ownedCount' => 0,
    'includedCount' => 0,
])

@php
    $analyticsKey = 'mc_pub_author_' . $user->id . '_analytics';

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

@if($ownedCount + $includedCount > 0)
    <div class="d-flex align-items-center mb-2">
        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                type="button"
                id="author-analytics-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#author-analytics"
                aria-expanded="false"
                aria-controls="author-analytics"
                data-analytics-storage-key="{{ $analyticsKey }}">
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
            <x-public.author.charts.timeline
                :pub-timeline="$profile->pubTimeline"
            />

            <x-public.author.charts.tags
                :tag-names="$profile->chartTagNames"
                :tag-counts="$profile->chartTagCounts"
            />

            <x-public.author.charts.coauthors
                :coauthor-names="$profile->chartCoauthorNames"
                :coauthor-counts="$profile->chartCoauthorCounts"
            />

            <x-public.author.charts.top-viewed
                :names="$topViewedNames"
                :counts="$topViewedCounts"
                :urls="$topViewedUrls"
            />

            <x-public.author.charts.top-downloaded
                :names="$topDownloadedNames"
                :counts="$topDownloadedCounts"
                :urls="$topDownloadedUrls"
            />
        </div>
    </div>
@endif

@push('scripts')
    <script>
        (function () {
            const STORAGE_KEY = @json($analyticsKey);
            const panel = document.getElementById('author-analytics');
            const toggle = document.getElementById('author-analytics-toggle');
            const chevron = document.getElementById('author-analytics-chevron');

            if (panel) {
                if (localStorage.getItem(STORAGE_KEY) === 'true') {
                    panel.classList.add('show');

                    if (chevron) {
                        chevron.style.transform = 'rotate(90deg)';
                    }

                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                }

                panel.addEventListener('show.bs.collapse', () => {
                    if (chevron) {
                        chevron.style.transform = 'rotate(90deg)';
                    }

                    localStorage.setItem(STORAGE_KEY, 'true');
                });

                panel.addEventListener('hide.bs.collapse', () => {
                    if (chevron) {
                        chevron.style.transform = 'rotate(0deg)';
                    }

                    localStorage.setItem(STORAGE_KEY, 'false');
                });

                panel.addEventListener('shown.bs.collapse', () => {
                    panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                });
            }
        })();
    </script>
@endpush
