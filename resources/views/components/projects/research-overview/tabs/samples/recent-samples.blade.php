@props([
    'project',
    'metrics' => [],
])

@php
    $recentSamples = collect($metrics['recentSamples'] ?? []);
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-clock me-1"></i>Recent Samples
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Recently updated samples and their core research links.
                </p>
            </div>

            <a href="{{ route('projects.entities.index', [$project, 'category' => 'experimental']) }}"
               class="btn btn-sm btn-outline-success">
                View Samples
            </a>
        </div>

        @if($recentSamples->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-cubes fa-2x mb-2"></i>
                <div class="fw-semibold">No samples yet</div>
                <div style="font-size:.85rem;">Samples will appear after studies or experiment data are loaded.</div>
            </div>
        @else
            <div class="d-flex flex-column gap-2">
                @foreach($recentSamples as $sample)
                    @php
                        $description = filled($sample->description ?? null)
                            ? $sample->description
                            : ($sample->summary ?? null);
                    @endphp

                    <div class="border rounded p-3">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="min-width-0">
                                <a href="{{ route('projects.entities.show', [$project, $sample->id]) }}"
                                   class="fw-semibold text-decoration-none d-block text-break"
                                   style="overflow-wrap:anywhere;">
                                    <i class="fas fa-cubes text-muted me-1"></i>{{ $sample->name }}
                                </a>

                                <div class="text-muted" style="font-size:.76rem;">
                                    Updated {{ $sample->updated_at?->diffForHumans() }}
                                </div>
                            </div>

                            <span class="badge text-bg-light border flex-shrink-0">
                                {{ $sample->category ?? 'sample' }}
                            </span>
                        </div>

                        @if(filled($description))
                            <div class="text-muted mb-2"
                                 style="font-size:.82rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                {{ $description }}
                            </div>
                        @else
                            <div class="text-muted fst-italic mb-2" style="font-size:.82rem;">
                                No description provided.
                            </div>
                        @endif

                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Studies</div>
                                    <div class="fw-semibold">{{ number_format((int) ($sample->experiments_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Processes</div>
                                    <div class="fw-semibold">{{ number_format((int) ($sample->activities_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Measurements</div>
                                    <div class="fw-semibold">{{ number_format((int) ($sample->entity_states_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Files</div>
                                    <div class="fw-semibold">{{ number_format((int) ($sample->files_count ?? 0)) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @if((int) ($sample->datasets_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $sample->datasets_count) }} dataset{{ (int) $sample->datasets_count === 1 ? '' : 's' }}
                                </span>
                            @endif

                            @if((int) ($sample->workflows_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $sample->workflows_count) }} workflow{{ (int) $sample->workflows_count === 1 ? '' : 's' }}
                                </span>
                            @endif

                            @if((int) ($sample->tags_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $sample->tags_count) }} tag{{ (int) $sample->tags_count === 1 ? '' : 's' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
