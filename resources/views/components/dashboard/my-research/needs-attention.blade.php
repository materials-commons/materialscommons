<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-exclamation-circle me-1"></i>Needs Attention
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Actionable items that may affect publication readiness, metadata quality, or project organization.
                </p>
            </div>

            @if($hasItems)
                <span class="badge text-bg-danger">
                    {{ number_format($totalCount) }} total
                </span>
            @else
                <span class="badge text-bg-success">
                    All clear
                </span>
            @endif
        </div>

        @if($hasItems)
            <div class="row g-2">
                @foreach($items as $item)
                    <div class="col-12 col-lg-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0 text-muted">
                                    <i class="{{ $item['icon'] }} fa-lg"></i>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                                        <h6 class="mb-0 fw-semibold">
                                            {{ $item['title'] }}
                                        </h6>

                                        <span class="badge {{ $item['badgeClass'] }}">
                                            {{ number_format($item['count']) }}
                                        </span>
                                    </div>

                                    <p class="text-muted mb-2" style="font-size:.82rem;">
                                        {{ $item['description'] }}
                                    </p>

                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        @if($item['actionUrl'])
                                            <a href="{{ $item['actionUrl'] }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                {{ $item['actionLabel'] }}
                                            </a>
                                        @else
                                            <span class="text-muted" style="font-size:.78rem;">
                                                <i class="fas fa-arrow-down me-1"></i>{{ $item['actionLabel'] }} in the tabs below
                                            </span>
                                        @endif

                                        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-1 js-needs-attention-toggle"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#needs-attention-details-{{ $item['key'] }}"
                                                aria-expanded="false"
                                                aria-controls="needs-attention-details-{{ $item['key'] }}"
                                                data-storage-key="mc_dashboard_my_research_needs_attention_{{ $item['key'] }}">
                                            <i class="fas fa-chevron-right fa-fw js-needs-attention-chevron"
                                               style="transition:transform .2s; font-size:.7rem;"></i>
                                            <span style="font-size:.78rem;">
                                                Show affected items
                                            </span>
                                        </button>
                                    </div>

                                    <div class="collapse mt-3 js-needs-attention-details"
                                         id="needs-attention-details-{{ $item['key'] }}"
                                         data-storage-key="mc_dashboard_my_research_needs_attention_{{ $item['key'] }}">
                                        <div class="border-top pt-2">
                                            <div class="text-muted mb-2"
                                                 style="font-size:.72rem; letter-spacing:.03em; text-transform:uppercase;">
                                                {{ $item['detailsLabel'] }}
                                            </div>

                                            <div class="list-group list-group-flush">
                                                @foreach($item['details'] as $detail)
                                                    <a href="{{ $detail['url'] }}"
                                                       class="list-group-item list-group-item-action px-0 py-2 border-0">
                                                        <div class="d-flex align-items-start gap-2">
                                                            <i class="{{ $detail['icon'] }} text-muted mt-1"
                                                               style="font-size:.75rem;"></i>

                                                            <div class="flex-grow-1">
                                                                <div class="fw-semibold"
                                                                     style="font-size:.86rem;">
                                                                    {{ $detail['label'] }}
                                                                </div>
                                                                <div class="text-muted"
                                                                     style="font-size:.75rem;">
                                                                    {{ $detail['meta'] }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>

                                            @if($item['remainingCount'] > 0)
                                                <div class="text-muted mt-2" style="font-size:.78rem;">
                                                    <i class="fas fa-ellipsis-h me-1"></i>
                                                    {{ number_format($item['remainingCount']) }} more not shown
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="border rounded bg-light p-3 text-muted">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>
                        No high-priority research, dataset, license, or metadata issues were found.
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            document.querySelectorAll('.js-needs-attention-details').forEach(panel => {
                const storageKey = panel.getAttribute('data-storage-key');
                const toggle = document.querySelector('[data-bs-target="#' + panel.id + '"]');
                const chevron = toggle ? toggle.querySelector('.js-needs-attention-chevron') : null;
                const label = toggle ? toggle.querySelector('span') : null;

                if (localStorage.getItem(storageKey) === 'true') {
                    panel.classList.add('show');

                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'true');
                    }

                    if (chevron) {
                        chevron.style.transform = 'rotate(90deg)';
                    }

                    if (label) {
                        label.textContent = 'Hide affected items';
                    }
                }

                panel.addEventListener('show.bs.collapse', () => {
                    localStorage.setItem(storageKey, 'true');

                    if (chevron) {
                        chevron.style.transform = 'rotate(90deg)';
                    }

                    if (label) {
                        label.textContent = 'Hide affected items';
                    }
                });

                panel.addEventListener('hide.bs.collapse', () => {
                    localStorage.setItem(storageKey, 'false');

                    if (chevron) {
                        chevron.style.transform = 'rotate(0deg)';
                    }

                    if (label) {
                        label.textContent = 'Show affected items';
                    }
                });
            });
        })();
    </script>
@endpush
