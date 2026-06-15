@props([
    'project',
])

@php
    $analyticsKey = 'mc_project_research_overview_analytics_' . $project->id;

    $hasAnyChartData = collect([
        (int) ($project->file_count ?? 0),
        (int) ($project->directory_count ?? 0),
        (int) ($project->experiments_count ?? 0),
        (int) ($project->entities_count ?? 0),
        (int) ($project->published_datasets_count ?? 0),
        (int) ($project->unpublished_datasets_count ?? 0),
        (int) ($project->size ?? 0),
        (int) ($project->entityAttributesCount ?? 0),
        (int) ($project->activityAttributesCount ?? 0),
    ])->some(fn($value) => $value > 0);
@endphp

<div class="d-flex align-items-center mb-2">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="project-research-overview-analytics-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#project-research-overview-analytics"
            aria-expanded="false"
            aria-controls="project-research-overview-analytics">
        <i class="fas fa-chevron-right fa-fw"
           id="project-research-overview-analytics-chevron"
           style="transition:transform .2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Project Analytics
        </span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<div class="collapse mb-4" id="project-research-overview-analytics">
    @if(!$hasAnyChartData)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 background-white text-center text-muted">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <div class="fw-semibold">No analytics available yet</div>
                <div style="font-size:.85rem;">
                    Analytics will appear after this project has files, studies, samples, datasets, or metadata.
                </div>
            </div>
        </div>
    @else
        <div class="row g-3">
            <div class="col-12 col-xl-6">
                <x-projects.research-overview.analytics.project-composition :project="$project"/>
            </div>

            <div class="col-12 col-xl-6">
                <x-projects.research-overview.analytics.dataset-status :project="$project"/>
            </div>

            <div class="col-12 col-xl-6">
                <x-projects.research-overview.analytics.metadata-coverage :project="$project"/>
            </div>

            <div class="col-12 col-xl-6">
                <x-projects.research-overview.analytics.storage :project="$project"/>
            </div>

            <div class="col-12 col-xl-6">
                <x-projects.research-overview.analytics.file-types :project="$project"/>
            </div>

            <div class="col-12 col-xl-6">
                <x-projects.research-overview.analytics.process-types :project="$project"/>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        (function () {
            const STORAGE_KEY = @json($analyticsKey);
            const panel = document.getElementById('project-research-overview-analytics');
            const toggle = document.getElementById('project-research-overview-analytics-toggle');
            const chevron = document.getElementById('project-research-overview-analytics-chevron');

            if (!panel) {
                return;
            }

            function renderAnalyticsCharts() {
                if (!window.Plotly || panel.dataset.rendered === 'true') {
                    return;
                }

                panel.dataset.rendered = 'true';
                window.dispatchEvent(new CustomEvent('mc:project-research-overview-analytics:render'));
            }

            function resizeAnalyticsCharts() {
                if (!window.Plotly) {
                    return;
                }

                panel.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
            }

            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                panel.classList.add('show');

                if (chevron) {
                    chevron.style.transform = 'rotate(90deg)';
                }

                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'true');
                }

                requestAnimationFrame(() => {
                    renderAnalyticsCharts();
                    resizeAnalyticsCharts();
                });
            }

            panel.addEventListener('show.bs.collapse', () => {
                if (chevron) {
                    chevron.style.transform = 'rotate(90deg)';
                }

                localStorage.setItem(STORAGE_KEY, 'true');
                renderAnalyticsCharts();
            });

            panel.addEventListener('hide.bs.collapse', () => {
                if (chevron) {
                    chevron.style.transform = 'rotate(0deg)';
                }

                localStorage.setItem(STORAGE_KEY, 'false');
            });

            panel.addEventListener('shown.bs.collapse', () => {
                resizeAnalyticsCharts();
            });
        })();
    </script>
@endpush
