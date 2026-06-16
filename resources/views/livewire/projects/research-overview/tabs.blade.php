<div>
    @php
        $membersCount = collect($project->team?->members ?? collect())->count();

        $datasetsCount = (int) ($project->published_datasets_count ?? 0)
            + (int) ($project->unpublished_datasets_count ?? 0);

        $healthLabel = match ($project->health) {
            'critical' => 'Critical',
            'warning' => 'Warning',
            null => 'Unknown',
            default => 'Healthy',
        };

        $healthColor = match ($project->health) {
            'critical' => 'danger',
            'warning' => 'warning',
            null => 'secondary',
            default => 'success',
        };
    @endphp

    <ul class="nav nav-pills mb-3" id="project-dashboard-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('overview')"
                    class="nav-link {{ $tab === 'overview' ? 'active' : '' }}">
                <i class="fas fa-home me-1"></i>Overview
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('files')"
                    class="nav-link {{ $tab === 'files' ? 'active' : '' }}">
                <i class="fas fa-folder-open me-1"></i>Files
                <span class="badge text-bg-primary ms-1">{{ number_format($project->file_count) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('studies')"
                    class="nav-link {{ $tab === 'studies' ? 'active' : '' }}">
                <i class="fas fa-flask me-1"></i>Studies
                <span class="badge text-bg-info ms-1">{{ number_format($project->experiments_count) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('datasets')"
                    class="nav-link {{ $tab === 'datasets' ? 'active' : '' }}">
                <i class="fas fa-database me-1"></i>Datasets
                <span class="badge text-bg-success ms-1">{{ number_format($datasetsCount) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('samples')"
                    class="nav-link {{ $tab === 'samples' ? 'active' : '' }}">
                <i class="fas fa-cubes me-1"></i>Samples
                <span class="badge text-bg-secondary ms-1">{{ number_format($project->entities_count) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('processes')"
                    class="nav-link {{ $tab === 'processes' ? 'active' : '' }}">
                <i class="fas fa-cogs me-1"></i>Processes
                <span class="badge text-bg-secondary ms-1">{{ number_format($project->activityAttributesCount ?? 0) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('metadata')"
                    class="nav-link {{ $tab === 'metadata' ? 'active' : '' }}">
                <i class="fas fa-clipboard-check me-1"></i>Metadata
                <span class="badge text-bg-warning ms-1">
                    {{ number_format(($project->entityAttributesCount ?? 0) + ($project->activityAttributesCount ?? 0)) }}
                </span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('collaborators')"
                    class="nav-link {{ $tab === 'collaborators' ? 'active' : '' }}">
                <i class="fas fa-users me-1"></i>Collaborators
                <span class="badge text-bg-primary ms-1">{{ number_format($membersCount) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('health')"
                    class="nav-link {{ $tab === 'health' ? 'active' : '' }}">
                <i class="fas fa-heartbeat me-1"></i>Health
                <span class="badge text-bg-{{ $healthColor }} ms-1">{{ $healthLabel }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('activity')"
                    class="nav-link {{ $tab === 'activity' ? 'active' : '' }}">
                <i class="fas fa-history me-1"></i>Activity
            </button>
        </li>
    </ul>

    <div wire:loading.delay class="card border-0 shadow-sm mb-3">
        <div class="card-body p-4 background-white text-center text-muted">
            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
            <div class="fw-semibold">Loading {{ ucfirst($tab) }}...</div>
        </div>
    </div>

    <div wire:loading.remove>
        @switch($tab)
            @case('files')
                <x-projects.research-overview.tabs.files
                    :project="$project"
                    :metrics="$metrics"
                />
                @break

            @case('studies')
                <x-projects.research-overview.tabs.studies
                    :project="$project"
                    :metrics="$metrics"
                />
                @break

            @case('datasets')
                <x-projects.research-overview.tabs.datasets :project="$project"/>
                @break

            @case('samples')
                <x-projects.research-overview.tabs.samples :project="$project"/>
                @break

            @case('processes')
                <x-projects.research-overview.tabs.processes :project="$project"/>
                @break

            @case('metadata')
                <x-projects.research-overview.tabs.metadata :project="$project"/>
                @break

            @case('collaborators')
                <x-projects.research-overview.tabs.collaborators :project="$project"/>
                @break

            @case('health')
                <x-projects.research-overview.tabs.health :project="$project"/>
                @break

            @case('activity')
                <x-projects.research-overview.tabs.activity :project="$project"/>
                @break

            @default
                <x-projects.research-overview.tabs.overview :project="$project"/>
        @endswitch
    </div>

    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('project-research-overview-tab-changed', function (event) {
                const tab = Array.isArray(event) ? event[0]?.tab : event.tab;

                if (!tab) {
                    return;
                }

                localStorage.setItem('mc_project_research_overview_tab_{{ $project->id }}', tab);
            });
        });
    </script>
</div>
