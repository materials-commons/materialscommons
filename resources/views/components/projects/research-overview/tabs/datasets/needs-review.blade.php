@props([
    'project',
    'metrics' => [],
])

@php
    $datasetsNeedingReview = collect($metrics['needsMetadata'] ?? [])->take(8);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-exclamation-circle me-1"></i>Datasets Needing Review
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Datasets missing metadata, files, tags, studies, authors, licenses, or DOI information.
                </p>
            </div>

            <span class="badge text-bg-{{ $datasetsNeedingReview->count() > 0 ? 'warning' : 'success' }}">
                {{ number_format($datasetsNeedingReview->count()) }} shown
            </span>
        </div>

        @if($datasetsNeedingReview->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <div class="fw-semibold">No obvious dataset issues</div>
                <div style="font-size:.85rem;">
                    Datasets have basic metadata and content signals.
                </div>
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach($datasetsNeedingReview as $dataset)
                    <a href="{{ route('projects.datasets.show', [$project, $dataset->id]) }}"
                       class="list-group-item list-group-item-action px-0">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="min-width-0">
                                <div class="fw-semibold text-truncate">
                                    <i class="fas fa-database text-muted me-1"></i>{{ $dataset->name }}
                                </div>

                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach($dataset->research_overview_issues as $issue)
                                        <span class="badge text-bg-warning">{{ $issue }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-end flex-shrink-0 text-muted" style="font-size:.72rem;">
                                {{ $dataset->updated_at?->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
