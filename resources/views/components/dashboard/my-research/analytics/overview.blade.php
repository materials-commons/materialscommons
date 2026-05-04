@props([
    'projects' => collect(),
    'activeProjects' => collect(),
    'recentlyAccessedProjects' => collect(),
    'archivedProjects' => collect(),
    'deletedProjects' => collect(),
    'datasets' => collect(),
])

@php
    $analyticsKey = 'mc_dashboard_my_research_analytics';

    $projects = collect($projects);
    $activeProjects = collect($activeProjects);
    $recentlyAccessedProjects = collect($recentlyAccessedProjects);
    $archivedProjects = collect($archivedProjects);
    $deletedProjects = collect($deletedProjects);
    $datasets = collect($datasets);

    /*
     * Analytics must remain aggregate-only. Do not enumerate project/dataset files here.
     * These charts use passed collections and summary/count fields like file_count,
     * size, files_count, activities_count, entities_count, etc.
     */
    $publishedDatasets = $datasets->filter(fn ($dataset) => filled($dataset->published_at ?? null));
    $draftDatasets = $datasets->reject(fn ($dataset) => filled($dataset->published_at ?? null));

    $licenseCounts = $datasets
        ->map(fn ($dataset) => blank($dataset->license ?? null) ? 'Missing License' : $dataset->license)
        ->countBy()
        ->sortDesc();

    $projectStorage = $projects
        ->filter(fn ($project) => (int) ($project->size ?? 0) > 0)
        ->sortByDesc(fn ($project) => (int) ($project->size ?? 0))
        ->take(10)
        ->values();

    $fileCountProjects = $projects
        ->filter(fn ($project) => (int) ($project->file_count ?? 0) > 0)
        ->sortByDesc(fn ($project) => (int) ($project->file_count ?? 0))
        ->take(10)
        ->values();

    $datasetCounts = [
        'Files' => $datasets->sum(fn ($dataset) => (int) ($dataset->files_count ?? 0)),
        'Activities' => $datasets->sum(fn ($dataset) => (int) ($dataset->activities_count ?? 0)),
        'Entities' => $datasets->sum(fn ($dataset) => (int) ($dataset->entities_count ?? 0)),
        'Experiments' => $datasets->sum(fn ($dataset) => (int) ($dataset->experiments_count ?? 0)),
        'Workflows' => $datasets->sum(fn ($dataset) => (int) ($dataset->workflows_count ?? 0)),
    ];

    $hasAnyChartData = collect([
        $projects->count(),
        $datasets->count(),
        $activeProjects->count(),
        $recentlyAccessedProjects->count(),
        $archivedProjects->count(),
        $deletedProjects->count(),
        $publishedDatasets->count(),
        $draftDatasets->count(),
        $licenseCounts->sum(),
        $projectStorage->sum(fn ($project) => (int) ($project->size ?? 0)),
        $fileCountProjects->sum(fn ($project) => (int) ($project->file_count ?? 0)),
        collect($datasetCounts)->sum(),
    ])->some(fn ($value) => (float) $value > 0);
@endphp

<div class="d-flex align-items-center mb-2">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="my-research-analytics-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#my-research-analytics"
            aria-expanded="false"
            aria-controls="my-research-analytics">
        <i class="fas fa-chevron-right fa-fw"
           id="my-research-analytics-chevron"
           style="transition:transform .2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Analytics
        </span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<div class="collapse mb-3" id="my-research-analytics">
    @if(!$hasAnyChartData)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 background-white text-center text-muted">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <div class="fw-semibold">No analytics available yet</div>
                <div style="font-size:.85rem;">
                    Analytics will appear after projects or datasets have aggregate metadata.
                </div>
            </div>
        </div>
    @else
        <div class="row g-3">
            <div class="col-12 col-xl-6">
                <x-dashboard.my-research.analytics.charts.project-status
                    :active-projects="$activeProjects"
                    :recently-accessed-projects="$recentlyAccessedProjects"
                    :archived-projects="$archivedProjects"
                    :deleted-projects="$deletedProjects"
                />
            </div>

            <div class="col-12 col-xl-6">
                <x-dashboard.my-research.analytics.charts.dataset-status
                    :published-datasets="$publishedDatasets"
                    :draft-datasets="$draftDatasets"
                />
            </div>

            <div class="col-12 col-xl-6">
                <x-dashboard.my-research.analytics.charts.license-coverage
                    :license-counts="$licenseCounts"
                />
            </div>

            <div class="col-12 col-xl-6">
                <x-dashboard.my-research.analytics.charts.dataset-contents
                    :dataset-counts="$datasetCounts"
                />
            </div>

            <div class="col-12 col-xl-6">
                <x-dashboard.my-research.analytics.charts.project-storage
                    :projects="$projectStorage"
                />
            </div>

            <div class="col-12 col-xl-6">
                <x-dashboard.my-research.analytics.charts.project-file-counts
                    :projects="$fileCountProjects"
                />
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        (function () {
            const STORAGE_KEY = @json($analyticsKey);
            const panel = document.getElementById('my-research-analytics');
            const toggle = document.getElementById('my-research-analytics-toggle');
            const chevron = document.getElementById('my-research-analytics-chevron');

            if (!panel) return;

            function renderAnalyticsCharts() {
                if (!window.Plotly || panel.dataset.rendered === 'true') {
                    return;
                }

                panel.dataset.rendered = 'true';
                window.dispatchEvent(new CustomEvent('mc:my-research-analytics:render'));
            }

            function resizeAnalyticsCharts() {
                if (!window.Plotly) {
                    return;
                }

                panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
            }

            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                panel.classList.add('show');
                if (chevron) chevron.style.transform = 'rotate(90deg)';
                if (toggle) toggle.setAttribute('aria-expanded', 'true');

                requestAnimationFrame(() => {
                    renderAnalyticsCharts();
                    resizeAnalyticsCharts();
                });
            }

            panel.addEventListener('show.bs.collapse', () => {
                if (chevron) chevron.style.transform = 'rotate(90deg)';
                localStorage.setItem(STORAGE_KEY, 'true');
                renderAnalyticsCharts();
            });

            panel.addEventListener('hide.bs.collapse', () => {
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                localStorage.setItem(STORAGE_KEY, 'false');
            });

            panel.addEventListener('shown.bs.collapse', () => {
                panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
            });
        })();
    </script>
@endpush
