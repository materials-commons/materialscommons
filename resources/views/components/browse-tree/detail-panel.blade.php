@props([
    'selectedItem',
])

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
                @isset($selectedItem['experiment'])
                    <div class="col-12">
                        <div class="mc-mini-meta">
                            <div class="text-muted small">Experiment</div>
                            <div class="fw-semibold">{{ $selectedItem['experiment'] }}</div>
                        </div>
                    </div>
                @endisset

                @isset($selectedItem['dateLabel'])
                    <div class="col-12">
                        <div class="mc-mini-meta">
                            <div class="text-muted small">Date</div>
                            <div class="fw-semibold">{{ $selectedItem['dateLabel'] }}</div>
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
                </div>
            </div>

            <div class="mc-related-box mb-3">
                <div class="fw-semibold mb-2">Related data</div>

                <div class="mc-related-row">
                    <i class="fas fa-vial text-success"></i>
                    <span>2 related samples</span>
                </div>

                <div class="mc-related-row">
                    <i class="fas fa-file-alt text-secondary"></i>
                    <span>4 related files</span>
                </div>

                <div class="mc-related-row">
                    <i class="fas fa-microchip text-primary"></i>
                    <span>1 related computation</span>
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
    @endif
</aside>
