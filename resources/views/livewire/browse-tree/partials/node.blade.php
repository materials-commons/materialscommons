@php
    $hasLoadedChildren = count($node['children'] ?? []) > 0;
    $isLazy = $node['lazy'] ?? false;
    $hasChildren = $hasLoadedChildren || $isLazy;
    $isExpanded = in_array($node['key'], $expandedNodeKeys, true);
    $isSelected = ($selectedItem['key'] ?? null) === $node['key'];
@endphp

<li class="mc-tree-node">
    @if($hasChildren)
        <button type="button"
                class="mc-tree-toggle"
                wire:click="toggleNode('{{ $node['key'] }}')"
                aria-expanded="{{ $isExpanded ? 'true' : 'false' }}">
            <i class="fas {{ $isExpanded ? 'fa-chevron-down' : 'fa-chevron-right' }}"></i>
            <i class="{{ $node['icon'] ?? 'fas fa-folder text-warning' }}"></i>
            <span class="mc-node-label">{{ $node['title'] }}</span>

            @isset($node['count'])
                <span class="mc-node-count">{{ $node['count'] }}</span>
            @endisset
        </button>

        @if($isExpanded)
            @if($hasLoadedChildren)
                <ul class="mc-tree-children">
                    @foreach($node['children'] as $child)
                        @include('livewire.browse-tree.partials.node', ['node' => $child])
                    @endforeach
                </ul>
            @else
                <ul class="mc-tree-children">
                    <li class="mc-tree-node">
                        <div class="mc-tree-item text-muted">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span class="mc-node-label">Loading...</span>
                        </div>
                    </li>
                </ul>
            @endif
        @endif
    @else
        <button type="button"
                class="mc-tree-item {{ $isSelected ? 'active' : '' }}"
                wire:click="selectItem('{{ $node['key'] }}')">
            <i class="{{ $node['icon'] ?? 'fas fa-circle text-muted' }}"></i>
            <span class="mc-node-label">{{ $node['title'] }}</span>

            @isset($node['badge'])
                <span class="badge text-bg-light border ms-auto">{{ $node['badge'] }}</span>
            @endisset
        </button>
    @endif
</li>
