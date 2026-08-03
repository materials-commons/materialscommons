@props([
    'node',
])

<button type="button"
        class="mc-tree-action"
        wire:click="toggleDirectoryFiles('{{ $node['directoryKey'] }}')">
    <i class="{{ $node['icon'] ?? 'fas fa-eye text-muted' }}"></i>
    <span class="mc-node-label">{{ $node['title'] }}</span>
</button>
