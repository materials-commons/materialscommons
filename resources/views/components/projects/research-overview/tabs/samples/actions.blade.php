@props([
    'project',
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-bolt me-1"></i>Sample Actions
        </h6>

        <p class="text-muted" style="font-size:.85rem;">
            Common actions for reviewing samples, improving attributes, and connecting samples to research context.
        </p>

        <div class="d-grid gap-2">
            <a href="{{ route('projects.entities.index', [$project, 'category' => 'experimental']) }}"
               class="btn btn-sm btn-outline-success text-start">
                <i class="fas fa-cubes me-1"></i>View Samples
            </a>

            <a href="{{ route('projects.data-dictionary.entities', [$project]) }}"
               class="btn btn-sm btn-outline-secondary text-start">
                <i class="fas fa-list me-1"></i>Sample Attributes
            </a>

            <a href="{{ route('projects.experiments.index', [$project]) }}"
               class="btn btn-sm btn-outline-info text-start">
                <i class="fas fa-flask me-1"></i>Review Studies
            </a>

            <button type="button"
                    class="btn btn-sm btn-outline-secondary text-start js-project-dashboard-show-tab"
                    data-tab-target="#tab-project-dashboard-processes">
                <i class="fas fa-cogs me-1"></i>Review Processes
            </button>

            <button type="button"
                    class="btn btn-sm btn-outline-warning text-start js-project-dashboard-show-tab"
                    data-tab-target="#tab-project-dashboard-metadata">
                <i class="fas fa-clipboard-check me-1"></i>Review Metadata
            </button>
        </div>
    </div>
</div>
