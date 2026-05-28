@php
    $expandedNodeKeys = $expandedNodeKeys ?? [];
    $selectedItem = $selectedItem ?? null;

    $nodeKey = (string) ($node['key'] ?? (($node['kind'] ?? 'node') . '-' . ($node['type'] ?? 'unknown') . '-' . ($node['title'] ?? 'untitled')));
    $wireKey = 'browse-tree-node-' . md5($nodeKey);

    $hasLoadedChildren = count($node['children'] ?? []) > 0;
    $isLazy = $node['lazy'] ?? false;
    $isFolder = ($node['kind'] ?? null) === 'folder';
    $hasChildren = $hasLoadedChildren || $isLazy || $isFolder;
    $isExpanded = in_array($node['key'], $expandedNodeKeys, true);
    $isSelected = ($selectedItem['key'] ?? null) === $node['key'];

    $nodeCount = $node['count'] ?? null;
@endphp

<li class="mc-tree-node" wire:key="{{ $wireKey }}">
    @if(($node['kind'] ?? null) === 'action')
        <x-browse-tree.action-node :node="$node" />
    @elseif(($node['kind'] ?? null) === 'message')
        <x-browse-tree.message-node :node="$node" />
    @elseif($hasChildren)
        <div class="mc-tree-branch {{ $isSelected ? 'active' : '' }}">
            <button type="button"
                    class="mc-tree-toggle-icon"
                    wire:click="toggleNode('{{ $node['key'] }}')"
                    aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                    aria-label="{{ $isExpanded ? 'Collapse' : 'Expand' }} {{ $node['title'] }}">
                <i class="fas {{ $isExpanded ? 'fa-chevron-down' : 'fa-chevron-right' }}"></i>
            </button>

            <button type="button"
                    class="mc-tree-toggle"
                    wire:click="selectNode(
                        '{{ $node['key'] }}',
                        @js($node['title'] ?? 'Untitled'),
                        '{{ $node['type'] ?? 'folder' }}',
                        '{{ $node['kind'] ?? 'folder' }}',
                        @js($node['icon'] ?? 'fas fa-folder text-warning'),
                        @js($nodeCount),
                        @js($isLazy),
                        @js($isExpanded)
                    )"
                    aria-label="{{ $node['title'] }}">
                <i class="{{ $node['icon'] ?? 'fas fa-folder text-warning' }}"></i>
                <span class="mc-node-label">{{ $node['title'] }}</span>

                @isset($node['count'])
                    <span class="mc-node-count">{{ $node['count'] }}</span>
                @endisset
            </button>
        </div>

        @if($isExpanded)
            @if($hasLoadedChildren)
                <ul class="mc-tree-children">
                    @foreach($node['children'] as $child)
                        @include('livewire.browse-tree.partials.node', [
                            'node' => $child,
                            'expandedNodeKeys' => $expandedNodeKeys,
                            'selectedItem' => $selectedItem,
                        ])
                    @endforeach
                </ul>
            @elseif($isLazy)
                <ul class="mc-tree-children">
                    <li class="mc-tree-node" wire:key="{{ $wireKey }}-loading">
                        <div class="mc-tree-item text-muted">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span class="mc-node-label">Loading...</span>
                        </div>
                    </li>
                </ul>
            @else
                <ul class="mc-tree-children">
                    <li class="mc-tree-node" wire:key="{{ $wireKey }}-empty">
                        <div class="mc-tree-message">
                            <i class="fas fa-info-circle text-muted"></i>
                            <span class="mc-node-label">No items to show at this level.</span>
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
