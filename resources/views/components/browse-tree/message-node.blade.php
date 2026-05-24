@props([
    'node',
])

<div class="mc-tree-message">
    <i class="{{ $node['icon'] ?? 'fas fa-info-circle text-muted' }}"></i>
    <span class="mc-node-label">{{ $node['title'] }}</span>
</div>
