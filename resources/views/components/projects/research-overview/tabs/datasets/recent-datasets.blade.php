@props([
    'project',
    'metrics' => [],
])

@php
    $recentDatasets = collect($metrics['recentDatasets'] ?? []);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-clock me-1"></i>Recent Datasets
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Recently updated datasets and their publication/content state.
                </p>
            </div>

            <a href="{{ route('projects.datasets.index', [$project]) }}"
               class="btn btn-sm btn-outline-success">
                View Datasets
            </a>
        </div>

        @if($recentDatasets->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-database fa-2x mb-2"></i>
                <div class="fw-semibold">No datasets yet</div>
                <div style="font-size:.85rem;">Create a dataset to prepare data for publication.</div>
            </div>
        @else
            <div class="d-flex flex-column gap-2">
                @foreach($recentDatasets as $dataset)
                    @php
                        $statusLabel = 'Draft';
                        $statusColor = 'warning';

                        if (filled($dataset->published_at)) {
                            $statusLabel = 'Published';
                            $statusColor = 'success';
                        } elseif (filled($dataset->privately_published_at)) {
                            $statusLabel = 'Private';
                            $statusColor = 'secondary';
                        }

                        $description = filled($dataset->description ?? null)
                            ? $dataset->description
                            : ($dataset->summary ?? null);
                    @endphp

                    <div class="border rounded p-3">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="min-width-0">
                                <a href="{{ route('projects.datasets.show', [$project, $dataset->id]) }}"
                                   class="fw-semibold text-decoration-none d-block text-break">
                                    <i class="fas fa-database text-muted me-1"></i>{{ $dataset->name }}
                                </a>

                                <div class="text-muted" style="font-size:.76rem;">
                                    Updated {{ $dataset->updated_at?->diffForHumans() }}
                                </div>
                            </div>

                            <span class="badge text-bg-{{ $statusColor }} flex-shrink-0">
                                {{ $statusLabel }}
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
                                    <div class="text-muted" style="font-size:.7rem;">Files</div>
                                    <div class="fw-semibold">{{ number_format((int) ($dataset->files_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Studies</div>
                                    <div class="fw-semibold">{{ number_format((int) ($dataset->experiments_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Views</div>
                                    <div class="fw-semibold">{{ number_format((int) ($dataset->views_count ?? 0)) }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="border rounded bg-light p-2 h-100">
                                    <div class="text-muted" style="font-size:.7rem;">Downloads</div>
                                    <div class="fw-semibold">{{ number_format((int) ($dataset->downloads_count ?? 0)) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @if((int) ($dataset->entities_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $dataset->entities_count) }} sample{{ (int) $dataset->entities_count === 1 ? '' : 's' }}
                                </span>
                            @endif

                            @if((int) ($dataset->activities_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $dataset->activities_count) }} process{{ (int) $dataset->activities_count === 1 ? '' : 'es' }}
                                </span>
                            @endif

                            @if((int) ($dataset->workflows_count ?? 0) > 0)
                                <span class="badge text-bg-light border">
                                    {{ number_format((int) $dataset->workflows_count) }} workflow{{ (int) $dataset->workflows_count === 1 ? '' : 's' }}
                                </span>
                            @endif

                            @if(filled($dataset->license ?? null))
                                <span class="badge text-bg-light border">
                                    <i class="fas fa-balance-scale me-1"></i>{{ $dataset->license }}
                                </span>
                            @else
                                <span class="badge text-bg-warning">
                                    Missing license
                                </span>
                            @endif

                            @if(filled($dataset->doi ?? null))
                                <span class="badge text-bg-light border">
                                    DOI
                                </span>
                            @elseif(filled($dataset->published_at))
                                <span class="badge text-bg-warning">
                                    Missing DOI
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
