@props([
    'project',
    'metrics' => [],
])

@php
    $needsReview = collect($metrics['needsReview'] ?? []);
    $missingAuthorDatasets = collect($metrics['datasetsWithoutAuthors'] ?? [])->take(5);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-exclamation-circle me-1"></i>Collaborators Needing Review
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Team and dataset author metadata issues.
                </p>
            </div>

            <span class="badge text-bg-{{ $needsReview->isNotEmpty() ? 'warning' : 'success' }}">
                {{ number_format($needsReview->count()) }} issue{{ $needsReview->count() === 1 ? '' : 's' }}
            </span>
        </div>

        @if($needsReview->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <div class="fw-semibold">No obvious collaborator issues</div>
                <div style="font-size:.85rem;">
                    Team and dataset author metadata look reasonable.
                </div>
            </div>
        @else
            <div class="list-group list-group-flush mb-3">
                @foreach($needsReview as $issue)
                    <div class="list-group-item px-0">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <div class="fw-semibold text-break">
                                    {{ $issue['label'] }}
                                </div>
                                <div class="text-muted" style="font-size:.78rem;">
                                    {{ $issue['hint'] }}
                                </div>
                            </div>

                            <span class="badge text-bg-{{ $issue['severity'] }} flex-shrink-0">
                                {{ $issue['type'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($missingAuthorDatasets->isNotEmpty())
            <div class="border rounded p-2 bg-light">
                <div class="text-muted small fw-semibold mb-1">Datasets Missing Authors</div>

                @foreach($missingAuthorDatasets as $dataset)
                    <a href="{{ route('projects.datasets.show', [$project, $dataset->id]) }}"
                       class="d-block text-decoration-none py-1">
                        <span class="fw-semibold text-break" style="font-size:.85rem;">
                            <i class="fas fa-database text-muted me-1"></i>{{ $dataset->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
