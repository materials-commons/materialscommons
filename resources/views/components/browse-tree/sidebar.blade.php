@props([
    'quickFilter',
    'typeCounts',
    'availableTags',
    'availableExperiments',
    'selectedTags',
])

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

        @foreach([
            'sample' => 'Samples',
            'computation' => 'Computations',
            'file' => 'Files',
            'dataset' => 'Datasets',
            'experiment' => 'Experiments',
        ] as $type => $label)
            <label class="mc-filter-row">
                <input type="checkbox"
                       class="form-check-input"
                       value="{{ $type }}"
                       wire:model.live="selectedTypes">
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
                        class="mc-search-chip {{ in_array($tag, $selectedTags, true) ? 'active' : '' }}"
                        wire:click="toggleTag('{{ $tag }}')">
                    {{ $tag }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="mc-sidebar-section">
        <div class="mc-sidebar-title">Suggested filters</div>

        <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('liver')">liver</button>
        <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('heat treatment')">heat treatment</button>
        <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('failed')">failed</button>
        <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('tensile')">tensile</button>
        <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('raw data')">raw data</button>
        <button type="button" class="mc-search-chip" wire:click="setSuggestedSearch('dataset')">dataset</button>
    </div>
</aside>
