<div class="mc-browse-tree">
    <x-browse-tree.styles />

    <div class="d-flex justify-content-end gap-2 mb-3">
        <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="expandAll">
            <i class="fas fa-plus-square me-1"></i> Expand all
        </button>

        <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="collapseAll">
            <i class="fas fa-minus-square me-1"></i> Collapse all
        </button>
    </div>

    <div class="mc-browser-shell">
        <div class="mc-browser-toolbar">
            <div class="row g-2 align-items-center">
                <div class="col-xl-5 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>

                        <input type="search"
                               class="form-control"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search samples, computations, files, datasets, experiments, tags, metadata...">

                        <button type="button" class="btn btn-outline-secondary" wire:click="clearSearch">
                            Clear
                        </button>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4">
                    <select class="form-select" wire:model.live="scope">
                        @if($project !== null || $focusedProjectId !== null)
                            <option value="project">
                                Current project
                                @if($project === null && $focusedProjectName !== null)
                                    — {{ $focusedProjectName }}
                                @endif
                            </option>
                        @endif
                        <option value="all">All projects</option>
                    </select>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4">
                    <select class="form-select" wire:model.live="groupBy">
                        <option value="project">Group by project</option>
                        <option value="type">Group by data type</option>
                    </select>
                </div>

                <div class="col-xl-3 col-lg-12">
                    <div class="text-xl-end text-muted small">
                        {{ $visibleLeafCount }} {{ Illuminate\Support\Str::plural('visible item', $visibleLeafCount) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="mc-browser-body">
            <aside class="mc-browser-sidebar">
                <div class="mc-sidebar-section">
                    <div class="mc-sidebar-title">Quick access</div>

                    <button type="button"
                            wire:click="setQuickFilter('all')"
                            class="mc-quick-link {{ $quickFilter === 'all' ? 'active' : '' }}">
                        <span><i class="fas fa-layer-group me-2"></i>All data</span>
                    </button>

                    <button type="button"
                            wire:click="setQuickFilter('recent')"
                            class="mc-quick-link {{ $quickFilter === 'recent' ? 'active' : '' }}">
                        <span><i class="fas fa-clock me-2"></i>Recently viewed</span>
                    </button>

                    <button type="button"
                            wire:click="setQuickFilter('pinned')"
                            class="mc-quick-link {{ $quickFilter === 'pinned' ? 'active' : '' }}">
                        <span><i class="fas fa-thumbtack me-2"></i>Pinned</span>
                    </button>
                </div>

                <div class="mc-sidebar-section">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mc-sidebar-title mb-0">Refine</div>

                        <button type="button"
                                class="btn btn-link btn-sm p-0 text-decoration-none"
                                wire:click="clearFacets">
                            Clear
                        </button>
                    </div>

                    <div class="mt-2">
                        <label class="form-label small fw-semibold mb-1">Updated</label>
                        <select class="form-select form-select-sm" wire:model.live="dateFilter">
                            <option value="any">Any time</option>
                            <option value="today">Today</option>
                            <option value="last-7-days">Last 7 days</option>
                            <option value="last-30-days">Last 30 days</option>
                            <option value="this-year">This year</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="form-label small fw-semibold mb-1">Experiment</label>
                        <select class="form-select form-select-sm" wire:model.live="experimentFilter">
                            <option value="any">All experiments</option>
                            @foreach($availableExperiments as $experiment)
                                <option value="{{ $experiment }}">{{ $experiment }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <x-browse-tree.file-display-controls />

                <div class="mc-sidebar-section">
                    <div class="mc-sidebar-title">Filter by type</div>

                    <label class="mc-filter-row">
                        <input type="checkbox"
                               class="form-check-input"
                               value="sample"
                               wire:model.live="selectedTypes">
                        <span>Samples</span>
                        <span class="text-muted ms-auto">{{ $typeCounts['sample'] ?? 0 }}</span>
                    </label>

                    <label class="mc-filter-row">
                        <input type="checkbox"
                               class="form-check-input"
                               value="computation"
                               wire:model.live="selectedTypes">
                        <span>Computations</span>
                        <span class="text-muted ms-auto">{{ $typeCounts['computation'] ?? 0 }}</span>
                    </label>

                    <label class="mc-filter-row">
                        <input type="checkbox"
                               class="form-check-input"
                               value="file"
                               wire:model.live="selectedTypes">
                        <span>Files</span>
                        <span class="text-muted ms-auto">{{ $typeCounts['file'] ?? 0 }}</span>
                    </label>

                    <label class="mc-filter-row">
                        <input type="checkbox"
                               class="form-check-input"
                               value="dataset"
                               wire:model.live="selectedTypes">
                        <span>Datasets</span>
                        <span class="text-muted ms-auto">{{ $typeCounts['dataset'] ?? 0 }}</span>
                    </label>

                    <label class="mc-filter-row">
                        <input type="checkbox"
                               class="form-check-input"
                               value="experiment"
                               wire:model.live="selectedTypes">
                        <span>Experiments</span>
                        <span class="text-muted ms-auto">{{ $typeCounts['experiment'] ?? 0 }}</span>
                    </label>
                </div>

                <div class="mc-sidebar-section">
                    <div class="mc-sidebar-title">Tags</div>

                    <div class="d-flex flex-wrap gap-1">
                        @foreach($availableTags as $tag)
                            <button type="button"
                                    class="mc-search-chip {{ in_array($tag, $selectedTags, true) ? 'active' : '' }}"
                                    wire:click="toggleTag('{{ $tag }}')">
                                {{ $tag }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mc-sidebar-section">
                    <div class="mc-sidebar-title">Suggested searches</div>

                    <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('liver')">liver</button>
                    <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('heat treatment')">heat treatment</button>
                    <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('failed')">failed</button>
                    <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('tensile')">tensile</button>
                    <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('raw data')">raw data</button>
                    <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('dataset')">dataset</button>
                </div>
            </aside>

            <main class="mc-tree-panel">
                <div class="mc-panel-header">
                    <div>
                        <h2 class="h5 mb-1">Data tree</h2>

                        <div class="text-muted small">
                            @if($scope === 'project')
                                Browsing the current project.
                            @else
                                Browsing across all projects.
                            @endif

                            @if($groupBy === 'type')
                                Grouped by data type.
                            @else
                                Grouped by project.
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mc-tree-scroll">
                    @if(count($tree) === 0)
                        <div class="mc-no-results">
                            <div class="fs-2 text-muted mb-2">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="fw-semibold">No matching data found</div>
                            <div class="text-muted small">
                                Try a broader term, a different scope, or clearing filters.
                            </div>
                        </div>
                    @else
                        <ul class="mc-tree">
                            @foreach($tree as $node)
                                @include('livewire.browse-tree.partials.node', ['node' => $node])
                            @endforeach
                        </ul>
                    @endif
                </div>
            </main>

            <aside class="mc-detail-panel">
                <div class="mc-panel-header">
                    <div>
                        <h2 class="h5 mb-1">Details</h2>
                        <div class="text-muted small">Selected item preview</div>
                    </div>
                </div>

                @if($selectedItem === null)
                    <div class="mc-detail-empty">
                        <div class="fs-2 text-muted mb-2">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <div class="fw-semibold">Select a leaf item</div>
                        <div class="text-muted small">
                            Click a sample, computation, file, dataset, or experiment to see details and actions.
                        </div>
                    </div>
                @else
                    <div class="mc-detail-content">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <div class="text-muted small text-uppercase fw-semibold">
                                    {{ $selectedItem['type'] ?? 'Item' }}
                                </div>

                                <h3 class="h4 mb-1">{{ $selectedItem['title'] }}</h3>

                                <div class="text-muted">{{ $selectedItem['project'] ?? '' }}</div>
                            </div>

                            <span class="badge text-bg-primary">
                                {{ $selectedItem['badge'] ?? Illuminate\Support\Str::title($selectedItem['type'] ?? 'Item') }}
                            </span>
                        </div>

                        <div class="mc-breadcrumb-box mb-3">
                            {{ $selectedItem['location'] ?? '' }}
                        </div>

                        <div class="row g-2 mb-3">
                            @isset($selectedItem['experiment'])
                                <div class="col-12">
                                    <div class="mc-mini-meta">
                                        <div class="text-muted small">Experiment</div>
                                        <div class="fw-semibold">{{ $selectedItem['experiment'] }}</div>
                                    </div>
                                </div>
                            @endisset

                            @isset($selectedItem['dateLabel'])
                                <div class="col-12">
                                    <div class="mc-mini-meta">
                                        <div class="text-muted small">Date</div>
                                        <div class="fw-semibold">{{ $selectedItem['dateLabel'] }}</div>
                                    </div>
                                </div>
                            @endisset
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold mb-1">Description</div>
                            <div class="text-muted">
                                {{ $selectedItem['description'] ?? '' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold mb-2">Tags and matched terms</div>

                            <div class="d-flex flex-wrap gap-2">
                                @foreach(($selectedItem['tags'] ?? []) as $tag)
                                    <span class="badge text-bg-light border">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mc-related-box mb-3">
                            <div class="fw-semibold mb-2">Related data</div>

                            <div class="mc-related-row">
                                <i class="fas fa-vial text-success"></i>
                                <span>2 related samples</span>
                            </div>

                            <div class="mc-related-row">
                                <i class="fas fa-file-alt text-secondary"></i>
                                <span>4 related files</span>
                            </div>

                            <div class="mc-related-row">
                                <i class="fas fa-microchip text-primary"></i>
                                <span>1 related computation</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @if(!blank($selectedItem['url'] ?? null))
                                <a href="{{ $selectedItem['url'] }}" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Open selected item
                                </a>
                            @else
                                <button type="button" class="btn btn-primary" disabled>
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Open selected item
                                </button>
                            @endif

                            <button type="button" class="btn btn-outline-secondary">
                                View containing project
                            </button>
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>
