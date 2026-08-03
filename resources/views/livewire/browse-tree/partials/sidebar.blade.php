@php
    $quickFilter = $quickFilter ?? 'all';
    $typeCounts = $typeCounts ?? [];
    $availableTags = $availableTags ?? [];
    $availableExperiments = $availableExperiments ?? [];
    $selectedTags = $selectedTags ?? [];
@endphp

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
            <div class="mc-sidebar-title mb-0">Refine loaded tree</div>

            <button type="button"
                    class="btn btn-link btn-sm p-0 text-decoration-none js-browse-tree-clear-facets">
                Clear
            </button>
        </div>

        <div class="mt-2">
            <label class="form-label small fw-semibold mb-1">Updated</label>
            <select class="form-select form-select-sm js-browse-tree-date-filter">
                <option value="any">Any time</option>
                <option value="today">Today</option>
                <option value="last-7-days">Last 7 days</option>
                <option value="last-30-days">Last 30 days</option>
                <option value="this-year">This year</option>
            </select>
        </div>

        <div class="mt-3">
            <label class="form-label small fw-semibold mb-1">Experiment</label>
            <select class="form-select form-select-sm js-browse-tree-experiment-filter">
                <option value="any">All experiments</option>
                @foreach($availableExperiments as $experiment)
                    <option value="{{ $experiment }}">{{ $experiment }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @include('livewire.browse-tree.partials.file-display-controls')

    <div class="mc-sidebar-section">
        <div class="mc-sidebar-title">Filter by type</div>

        @foreach([
            'sample' => 'Samples',
            'computation' => 'Computations',
            'file' => 'Files',
            'dataset' => 'Datasets',
            'experiment' => 'Experiments',
        ] as $type => $label)
            <label class="mc-filter-row">
                <input type="checkbox"
                       class="form-check-input js-browse-tree-type-filter"
                       value="{{ $type }}"
                       data-type="{{ $type }}"
                       checked>
                <span>{{ $label }}</span>
                <span class="text-muted ms-auto">{{ $typeCounts[$type] ?? 0 }}</span>
            </label>
        @endforeach
    </div>

    <div class="mc-sidebar-section">
        <div class="mc-sidebar-title">Tags</div>

        <div class="d-flex flex-wrap gap-1">
            @foreach($availableTags as $tag)
                <button type="button"
                        class="mc-search-chip js-browse-tree-tag-filter"
                        data-tag="{{ $tag }}">
                    {{ $tag }}
                </button>
            @endforeach

            @if(count($availableTags) === 0)
                <div class="text-muted small">
                    No tags in loaded data.
                </div>
            @endif
        </div>
    </div>

    <div class="mc-sidebar-section">
        <div class="mc-sidebar-title">Suggested filters</div>

        <button type="button"
                class="mc-search-chip js-browse-tree-suggested-filter"
                data-filter="liver">
            liver
        </button>

        <button type="button"
                class="mc-search-chip js-browse-tree-suggested-filter"
                data-filter="heat treatment">
            heat treatment
        </button>

        <button type="button"
                class="mc-search-chip js-browse-tree-suggested-filter"
                data-filter="failed">
            failed
        </button>

        <button type="button"
                class="mc-search-chip js-browse-tree-suggested-filter"
                data-filter="tensile">
            tensile
        </button>

        <button type="button"
                class="mc-search-chip js-browse-tree-suggested-filter"
                data-filter="raw data">
            raw data
        </button>

        <button type="button"
                class="mc-search-chip js-browse-tree-suggested-filter"
                data-filter="dataset">
            dataset
        </button>
    </div>
</aside>
