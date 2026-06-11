@props([
    'project',
])

@php
    $analyticsKey = 'mc_project_research_overview_analytics_' . $project->id;

    $datasetsCount = (int) ($project->published_datasets_count ?? 0)
        + (int) ($project->unpublished_datasets_count ?? 0);
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-chart-bar me-1"></i>Project Analytics
                </h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">
                    Visual placeholders for project composition, file types, process types, and readiness trends.
                </p>
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       role="switch"
                       id="project-research-overview-analytics-toggle"
                       data-project-analytics-toggle
                       data-analytics-key="{{ $analyticsKey }}"
                       data-bs-toggle="collapse"
                       data-bs-target="#project-research-overview-analytics"
                       aria-controls="project-research-overview-analytics">
                <label class="form-check-label text-muted small"
                       for="project-research-overview-analytics-toggle">
                    Show analytics
                </label>
            </div>
        </div>

        <div class="collapse" id="project-research-overview-analytics">
            <div class="row g-3 mt-1">
                <div class="col-12 col-xl-4">
                    <div class="border rounded p-3 h-100 text-center">
                        <i class="fas fa-chart-pie text-muted mb-2" style="font-size:2rem;"></i>
                        <h6 class="text-muted">Project Composition</h6>
                        <p class="text-muted mb-2" style="font-size:.82rem;">
                            Files, folders, studies, samples, datasets, and collaborator composition.
                        </p>
                        <div class="d-flex justify-content-center flex-wrap gap-2">
                            <span class="badge text-bg-primary">{{ number_format($project->file_count) }} files</span>
                            <span class="badge text-bg-info">{{ number_format($project->experiments_count) }} studies</span>
                            <span class="badge text-bg-success">{{ number_format($project->entities_count) }} samples</span>
                            <span class="badge text-bg-secondary">{{ number_format($datasetsCount) }} datasets</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="border rounded p-3 h-100 text-center">
                        <i class="fas fa-file-alt text-muted mb-2" style="font-size:2rem;"></i>
                        <h6 class="text-muted">File Types</h6>
                        <p class="text-muted mb-0" style="font-size:.82rem;">
                            Placeholder for file extension and MIME type distribution.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="border rounded p-3 h-100 text-center">
                        <i class="fas fa-cogs text-muted mb-2" style="font-size:2rem;"></i>
                        <h6 class="text-muted">Process Types</h6>
                        <p class="text-muted mb-0" style="font-size:.82rem;">
                            Placeholder for process/activity distribution and workflow coverage.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const toggle = document.querySelector('[data-project-analytics-toggle]');

            if (!toggle) {
                return;
            }

            const analyticsKey = toggle.getAttribute('data-analytics-key');
            const target = document.querySelector(toggle.getAttribute('data-bs-target'));

            if (!target || !analyticsKey) {
                return;
            }

            const savedState = localStorage.getItem(analyticsKey);
            const shouldShow = savedState === 'shown';

            toggle.checked = shouldShow;

            if (shouldShow) {
                bootstrap.Collapse.getOrCreateInstance(target, {toggle: false}).show();
            }

            target.addEventListener('shown.bs.collapse', function () {
                toggle.checked = true;
                localStorage.setItem(analyticsKey, 'shown');

                if (window.Plotly) {
                    document.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                }
            });

            target.addEventListener('hidden.bs.collapse', function () {
                toggle.checked = false;
                localStorage.setItem(analyticsKey, 'hidden');
            });
        })();
    </script>
@endpush
