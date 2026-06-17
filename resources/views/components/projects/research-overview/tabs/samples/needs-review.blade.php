@props([
    'project',
    'metrics' => [],
])

@php
    $samplesNeedingReview = collect($metrics['samplesNeedingReview'] ?? [])->take(8);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-exclamation-circle me-1"></i>Samples Needing Review
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Samples missing studies, processes, measurements, files, descriptions, or tags.
                </p>
            </div>

            <span class="badge text-bg-{{ $samplesNeedingReview->count() > 0 ? 'warning' : 'success' }}">
                {{ number_format($samplesNeedingReview->count()) }} shown
            </span>
        </div>

        @if($samplesNeedingReview->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <div class="fw-semibold">No obvious sample issues</div>
                <div style="font-size:.85rem;">
                    Samples have basic linkage and metadata signals.
                </div>
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach($samplesNeedingReview as $sample)
                    <a href="{{ route('projects.entities.show', [$project, $sample->id]) }}"
                       class="list-group-item list-group-item-action px-0">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="min-width-0">
                                <div class="fw-semibold text-break">
                                    <i class="fas fa-cubes text-muted me-1"></i>{{ $sample->name }}
                                </div>

                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach($sample->research_overview_issues as $issue)
                                        <span class="badge text-bg-warning">{{ $issue }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-end flex-shrink-0 text-muted" style="font-size:.72rem;">
                                {{ $sample->updated_at?->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
