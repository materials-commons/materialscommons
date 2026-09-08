@props([
    'project',
    'metrics' => [],
])

@php
    $processesNeedingReview = collect($metrics['processesNeedingReview'] ?? [])->take(8);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-exclamation-circle me-1"></i>Processes Needing Review
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Processes missing samples, studies, files, attributes, descriptions, or type information.
                </p>
            </div>

            <span class="badge text-bg-{{ $processesNeedingReview->count() > 0 ? 'warning' : 'success' }}">
                {{ number_format($processesNeedingReview->count()) }} shown
            </span>
        </div>

        @if($processesNeedingReview->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <div class="fw-semibold">No obvious process issues</div>
                <div style="font-size:.85rem;">
                    Processes have basic linkage and metadata signals.
                </div>
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach($processesNeedingReview as $process)
                    <a href="{{ route('projects.activities.show', [$project, $process->id]) }}"
                       class="list-group-item list-group-item-action px-0">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="min-width-0">
                                <div class="fw-semibold text-break">
                                    <i class="fas fa-cogs text-muted me-1"></i>{{ $process->name }}
                                </div>

                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach($process->research_overview_issues as $issue)
                                        <span class="badge text-bg-warning">{{ $issue }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-end flex-shrink-0 text-muted" style="font-size:.72rem;">
                                {{ $process->updated_at?->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
