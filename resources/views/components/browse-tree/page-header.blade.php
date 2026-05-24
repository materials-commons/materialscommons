@props([
    'eyebrow' => 'Data Browser',
    'title' => 'Browse Data',
    'subtitle' => '',
])

<div class="d-flex align-items-start justify-content-between mb-3">
    <div>
        <div class="text-muted small text-uppercase fw-semibold">{{ $eyebrow }}</div>
        <h1 class="h3 mb-1">{{ $title }}</h1>

        @if($subtitle !== '')
            <div class="text-muted">{{ $subtitle }}</div>
        @endif
    </div>

    {{ $actions ?? '' }}
</div>
