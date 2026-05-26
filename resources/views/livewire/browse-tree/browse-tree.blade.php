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
        <x-browse-tree.toolbar
            :project="$project"
            :focused-project-id="$focusedProjectId"
            :focused-project-name="$focusedProjectName"
            :visible-leaf-count="$visibleLeafCount"
        />

        <div class="mc-browser-body">
            <x-browse-tree.sidebar
                :quick-filter="$quickFilter"
                :type-counts="$typeCounts"
                :available-tags="$availableTags"
                :available-experiments="$availableExperiments"
                :selected-tags="$selectedTags"
            />

            <x-browse-tree.tree-panel
                :tree="$tree"
                :scope="$scope"
                :group-by="$groupBy"
                :expanded-node-keys="$expandedNodeKeys"
                :selected-item="$selectedItem"
            />

            <x-browse-tree.detail-panel
                :selected-item="$selectedItem"
            />
        </div>
    </div>
</div>
