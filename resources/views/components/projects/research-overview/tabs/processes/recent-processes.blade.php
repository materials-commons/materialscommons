@props([
    'project',
    'metrics' => [],
])

@php
    $recentProcesses = collect($metrics['recentProcesses'] ?? []);
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-clock me-1"></i>Recent Processes
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Recently updated processes and their core research links.
                </p>
            </div>

            <a href="{{ route('projects.data-dictionary.activities', [$project]) }}"
               class="btn btn-sm btn-outline-secondary">
                Process Attributes
            </a>
        </div>

        @if($recentProcesses->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-cogs fa-2x mb-2"></i>
                <div class="fw-semibold">No processes yet</div>
                <div style="font-size:.85rem;">Processes will appear after studies or experiment data are loaded.</div>
            </div>
        @else
            <div class="d-flex flex-column gap-2">
                @foreach($recentProcesses as $process)
                    @php
                        $description = filled($process->description ?? null)
                            ? $process->description
                            : ($process->summary ?? null);

                        $typeLabel = filled($process->atype ?? null)
                            ? $process->atype
                            : (filled($process->category ?? null) ? $process->category : 'Unspecified Type');
                    @endphp

                    <div class="border rounded p-3">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="min-width-0">
                                <a href="{{ route('projects.activities.show', [$project, $process->id]) }}"
                                   class="fw-semibold text-decoration-none d-block text-break"
                                   style="overflow-wrap:anywhere;">
                                    <i class="fas fa-cogs text-muted me-1"></i>{{ $process->name }}
                                </a>

                                <div class="text-muted" style="font-size:.76rem;">
                                    Updated {{ $process->updated_at?->diffForHumans() }}
                                </div>
                            </div>

                            <span class="badge text-bg-light border flex-shrink-0 text-break">
                                {{ $typeLabel }}
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
                                    <div class="text-muted" style="font-size:.7rem;">Samples</div>
                                    <div class="fw-semibold">{{ number_format((int) ($process->entities_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Files</div>
                                    <div class="fw-semibold">{{ number_format((int) ($process->files_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Studies</div>
                                    <div class="fw-semibold">{{ number_format((int) ($process->experiments_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Attributes</div>
                                    <div class="fw-semibold">{{ number_format((int) ($process->attributes_count ?? 0)) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @if((int) ($process->entity_states_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $process->entity_states_count) }} measurement{{ (int) $process->entity_states_count === 1 ? '' : 's' }}
                                </span>
                            @endif

                            @if((int) ($process->datasets_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $process->datasets_count) }} dataset{{ (int) $process->datasets_count === 1 ? '' : 's' }}
                                </span>
                            @endif

                            @if((int) ($process->workflows_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $process->workflows_count) }} workflow{{ (int) $process->workflows_count === 1 ? '' : 's' }}
                                </span>
                            @endif

                            @if((int) ($process->tags_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $process->tags_count) }} tag{{ (int) $process->tags_count === 1 ? '' : 's' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
