@props([
    'selectedItem',
])

@php
    $kind = $selectedItem['kind'] ?? null;
    $type = $selectedItem['type'] ?? null;

    $leafComponent = match ($type) {
        'sample' => 'browse-tree.details.leaf.sample-panel',
        'computation' => 'browse-tree.details.leaf.computation-panel',
        'file' => 'browse-tree.details.leaf.file-panel',
        'dataset' => 'browse-tree.details.leaf.dataset-panel',
        'experiment' => 'browse-tree.details.leaf.experiment-panel',
        'user' => 'browse-tree.details.leaf.user-panel',
        default => 'browse-tree.details.leaf.file-panel',
    };

    $folderComponent = match ($type) {
        'project' => 'browse-tree.details.folder.project-panel',
        'samples' => 'browse-tree.details.folder.samples-panel',
        'computations' => 'browse-tree.details.folder.computations-panel',
        'files' => 'browse-tree.details.folder.files-panel',
        'datasets' => 'browse-tree.details.folder.datasets-panel',
        'users' => 'browse-tree.details.folder.users-panel',
        'directory' => 'browse-tree.details.folder.directory-panel',
        default => 'browse-tree.details.folder.directory-panel',
    };

    $detailComponent = $kind === 'folder'
        ? $folderComponent
        : $leafComponent;
@endphp

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
        <x-dynamic-component
            :component="$detailComponent"
            :selected-item="$selectedItem"
        />
    @endif
</aside>
