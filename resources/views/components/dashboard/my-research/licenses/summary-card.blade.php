{{-- resources/views/components/dashboard/my-research/licenses/summary-card.blade.php --}}
@props([
    'label',
    'value',
    'hint' => '',
    'color' => 'secondary',
])

<div class="card border-0 shadow-sm h-100 text-center py-2">
    <div class="text-muted small">{{ $label }}</div>
    <div class="fw-bold fs-5 text-{{ $color }}">{{ number_format($value) }}</div>
    <div class="text-muted" style="font-size:.65rem;">{{ $hint }}</div>
</div>
