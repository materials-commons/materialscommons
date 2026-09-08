<div>
    {{-- ══ Private profile / research header ═════════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3 background-white">
            <div class="d-flex gap-4 align-items-start">
                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-light border"
                     style="width:80px; height:80px;">
                    <i class="fas fa-user fa-2x text-muted"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                        <div>
                            <h4 class="mb-1">My Research Overview</h4>
                            <p class="text-muted mb-2" style="font-size:.9rem;">
                                A private overview of your projects, datasets, publications, licenses, collaborators,
                                and metadata readiness.
                            </p>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('dashboard.projects.show') }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-folder-open me-1"></i>Projects
                            </a>
                            <a href="{{ route('dashboard.published-datasets.show') }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-database me-1"></i>Published Datasets
                            </a>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <span class="text-muted">
                            <i class="fas fa-user me-1" style="font-size:.8rem;"></i>{{ $user->name }}
                        </span>

                        @if($hasAffiliation)
                            <span class="text-muted">
                                <i class="fas fa-building me-1" style="font-size:.8rem;"></i>{{ $user->affiliations }}
                            </span>
                        @endif

                        @if($hasOrcid)
                            <span class="text-muted">
                                <i class="fas fa-id-badge me-1" style="font-size:.8rem;"></i>ORCID: {{ $user->orcid }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ KPI strip ═════════════════════════════════════════════════════════════════════ --}}
    <x-dashboard.my-research.kpi :projects="$projects" :archived-projects="$archivedProjects" :datasets="$datasets"/>

    {{-- ══ Needs attention placeholder ═══════════════════════════════════════════════════ --}}
    <x-dashboard.my-research.needs-attention/>

    {{-- ══ Content tabs ══════════════════════════════════════════════════════════════════ --}}
    <ul class="nav nav-pills mb-3" id="my-research-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('overview')"
                    class="nav-link {{ $tab === 'overview' ? 'active' : '' }}">
                <i class="fas fa-home me-1"></i>Overview
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('projects')"
                    class="nav-link {{ $tab === 'projects' ? 'active' : '' }}">
                <i class="fas fa-folder-open me-1"></i>Projects
                <span class="badge text-bg-primary ms-1">{{ number_format($projectsCount) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('datasets')"
                    class="nav-link {{ $tab === 'datasets' ? 'active' : '' }}">
                <i class="fas fa-database me-1"></i>Datasets
                <span class="badge text-bg-info ms-1">{{ number_format($datasetsCount) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('licenses')"
                    class="nav-link {{ $tab === 'licenses' ? 'active' : '' }}">
                <i class="fas fa-balance-scale me-1"></i>Licenses
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('papers')"
                    class="nav-link {{ $tab === 'papers' ? 'active' : '' }}">
                <i class="fas fa-file-alt me-1"></i>Papers
                <span class="badge text-bg-secondary ms-1">{{ number_format($papersCount) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('collaborators')"
                    class="nav-link {{ $tab === 'collaborators' ? 'active' : '' }}">
                <i class="fas fa-users me-1"></i>Collaborators
                <span class="badge text-bg-warning ms-1">{{ number_format($collaboratorsCount) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('tags')"
                    class="nav-link {{ $tab === 'tags' ? 'active' : '' }}">
                <i class="fas fa-tags me-1"></i>Tags
                <span class="badge text-bg-success ms-1">{{ number_format($tagCount) }}</span>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button type="button"
                    wire:click="setTab('communities')"
                    class="nav-link {{ $tab === 'communities' ? 'active' : '' }}">
                <i class="fas fa-layer-group me-1"></i>Communities
                <span class="badge text-bg-primary ms-1">{{ number_format($communitiesCount) }}</span>
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
            @case('projects')
                <x-dashboard.my-research.projects.overview
                    :projects="$tabData['projects'] ?? collect()"
                    :active-projects="$tabData['activeProjects'] ?? collect()"
                    :recently-accessed-projects="$tabData['recentlyAccessedProjects'] ?? collect()"
                    :archived-projects="$tabData['archivedProjects'] ?? collect()"
                    :deleted-projects="$tabData['deletedProjects'] ?? collect()"
                />
                @break

            @case('datasets')
                <x-dashboard.my-research.datasets.overview
                    :datasets="$tabData['datasets'] ?? collect()"
                    :projects="$tabData['projects'] ?? collect()"
                />
                @break

            @case('licenses')
                <x-dashboard.my-research.licenses.overview
                    :datasets="$tabData['datasets'] ?? collect()"
                    :projects="$tabData['projects'] ?? collect()"
                />
                @break

            @case('papers')
                <x-dashboard.my-research.papers.overview
                    :datasets="$tabData['datasets'] ?? collect()"
                    :projects="$tabData['projects'] ?? collect()"
                />
                @break

            @case('collaborators')
                <x-dashboard.my-research.collaborators.overview
                    :datasets="$tabData['datasets'] ?? collect()"
                    :projects="$tabData['projects'] ?? collect()"
                />
                @break

            @case('tags')
                <x-dashboard.my-research.tags.overview
                    :datasets="$tabData['datasets'] ?? collect()"
                    :listed-in-datasets="$tabData['listedInDatasets'] ?? collect()"
                />
                @break

            @case('communities')
                <x-dashboard.my-research.communities.overview
                    :communities="$tabData['communities'] ?? collect()"
                    :datasets="$tabData['datasets'] ?? collect()"
                    :listed-in-datasets="$tabData['listedInDatasets'] ?? collect()"
                />
                @break

            @case('metadata')
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 background-white">
                        <h6 class="card-title text-muted">
                            <i class="fas fa-clipboard-check me-1"></i>Metadata Readiness
                        </h6>
                        <p class="text-muted mb-3">
                            Placeholder for datasets missing descriptions, authors, tags, licenses, DOIs, publication
                            metadata, or other required information.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Missing / Incomplete</th>
                                    <th>Priority</th>
                                    <th>Suggested Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="5" class="text-muted text-center py-4">
                                        Metadata readiness placeholder
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @break

            @default
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <x-dashboard.my-research.research-summary />
                    </div>

                    <div class="col-12 col-lg-6">
                        <x-dashboard.my-research.recommended-actions />
                    </div>
                </div>
        @endswitch
    </div>

    <script>
        document.addEventListener('livewire:init', function () {
            const tabKey = 'mc_dashboard_my_research_tab';

            Livewire.on('dashboard-my-research-tab-changed', function (event) {
                const tab = Array.isArray(event) ? event[0]?.tab : event.tab;

                if (!tab) {
                    return;
                }

                localStorage.setItem(tabKey, tab);

                if (window.Plotly) {
                    document.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                }
            });

            const savedTab = localStorage.getItem(tabKey);

            if (savedTab) {
                @this.restoreTab(savedTab);
            }
        });
    </script>
</div>
