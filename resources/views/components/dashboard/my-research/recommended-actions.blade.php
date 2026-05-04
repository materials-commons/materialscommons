<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-tasks me-1"></i>Recommended Actions
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Suggested next steps based on your profile, projects, and datasets.
                </p>
            </div>

            @if($hasActions)
                <span class="badge text-bg-primary">
                    {{ count($actions) }}
                </span>
            @else
                <span class="badge text-bg-success">
                    All caught up
                </span>
            @endif
        </div>

        @if($hasActions)
            <div class="list-group list-group-flush">
                @foreach($actions as $action)
                    <div class="list-group-item px-0">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0 text-muted">
                                <i class="{{ $action['icon'] }}"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                    <div class="fw-semibold">
                                        {{ $action['title'] }}
                                    </div>

                                    <span class="badge {{ $action['badgeClass'] }}">
                                        {{ $action['badge'] }}
                                    </span>
                                </div>

                                <div class="text-muted mb-2" style="font-size:.82rem;">
                                    {{ $action['description'] }}
                                </div>

                                @if($action['modalTarget'])
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="{{ $action['modalTarget'] }}">
                                        {{ $action['actionLabel'] }}
                                    </button>
                                @elseif($action['actionUrl'])
                                    <a href="{{ $action['actionUrl'] }}"
                                       class="btn btn-sm btn-outline-primary">
                                        {{ $action['actionLabel'] }}
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size:.78rem;">
                                        <i class="fas fa-arrow-down me-1"></i>{{ $action['actionLabel'] }} in the tabs below
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="border rounded bg-light p-3 text-muted mt-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>
                        No recommended actions right now.
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>
