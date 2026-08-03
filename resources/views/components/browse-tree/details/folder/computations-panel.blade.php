@props([
    'selectedItem',
])

<div class="mc-detail-content">
    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
        <div>
            <div class="text-muted small text-uppercase fw-semibold">
                {{ $selectedItem['type'] ?? 'Item' }}
            </div>

            <h3 class="h4 mb-1">{{ $selectedItem['title'] }}</h3>

            <div class="text-muted">{{ $selectedItem['project'] ?? '' }}</div>
        </div>

        <span class="badge text-bg-primary">
            {{ $selectedItem['badge'] ?? Illuminate\Support\Str::title($selectedItem['type'] ?? 'Item') }}
        </span>
    </div>

    <div class="mc-breadcrumb-box mb-3">
        {{ $selectedItem['location'] ?? '' }}
    </div>

    <div class="row g-2 mb-3">
        <div class="col-12">
            <div class="mc-mini-meta">
                <div class="text-muted small">Branch status</div>
                <div class="fw-semibold">
                    {{ ($selectedItem['isExpanded'] ?? false) ? 'Expanded' : 'Collapsed' }}
                    @if($selectedItem['isLazy'] ?? false)
                        <span class="text-muted fw-normal">&middot; loads on expand</span>
                    @endif
                </div>
            </div>
        </div>

        @isset($selectedItem['childrenCount'])
            <div class="col-12">
                <div class="mc-mini-meta">
                    <div class="text-muted small">Direct items</div>
                    <div class="fw-semibold">{{ $selectedItem['childrenCount'] }}</div>
                </div>
            </div>
        @endisset
    </div>

    <div class="mb-3">
        <div class="fw-semibold mb-1">Description</div>
        <div class="text-muted">
            {{ $selectedItem['description'] ?? '' }}
        </div>
    </div>

    <div class="mb-3">
        <div class="fw-semibold mb-2">Tags and matched terms</div>

        <div class="d-flex flex-wrap gap-2">
            @foreach(($selectedItem['tags'] ?? []) as $tag)
                <span class="badge text-bg-light border">{{ $tag }}</span>
            @endforeach

            @if(count($selectedItem['tags'] ?? []) === 0)
                <span class="text-muted small">No tags available.</span>
            @endif
        </div>
    </div>

    <div class="d-grid gap-2">
        @if(!blank($selectedItem['url'] ?? null))
            <a href="{{ $selectedItem['url'] }}" class="btn btn-primary">
                <i class="fas fa-external-link-alt me-1"></i>
                Open selected item
            </a>
        @else
            <button type="button" class="btn btn-primary" disabled>
                <i class="fas fa-external-link-alt me-1"></i>
                Open selected item
            </button>
        @endif

        <button type="button" class="btn btn-outline-secondary">
            View containing project
        </button>
    </div>
</div>
