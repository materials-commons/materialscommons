@props([
    'tree',
    'scope',
    'groupBy',
    'expandedNodeKeys' => [],
    'selectedItem' => null,
])

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
                    @include('livewire.browse-tree.partials.node', [
                        'node' => $node,
                        'expandedNodeKeys' => $expandedNodeKeys,
                        'selectedItem' => $selectedItem,
                    ])
                @endforeach
            </ul>
        @endif
    </div>
</main>
