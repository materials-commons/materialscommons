@props([
    'project',
    'metrics' => [],
])

@php
    $recentStudies = collect($metrics['recentStudies'] ?? []);
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-clock me-1"></i>Recent Studies
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Recently updated studies and their core research objects.
                </p>
            </div>

            <a href="{{ route('projects.experiments.index', [$project]) }}"
               class="btn btn-sm btn-outline-info">
                View Studies
            </a>
        </div>

        @if($recentStudies->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-flask fa-2x mb-2"></i>
                <div class="fw-semibold">No studies yet</div>
                <div style="font-size:.85rem;">Create a study to organize files, samples, and processes.</div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th>Study</th>
                        <th class="text-end">Files</th>
                        <th class="text-end">Samples</th>
                        <th class="text-end">Processes</th>
                        <th class="text-end">Datasets</th>
                        <th>Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentStudies as $study)
                        <tr>
                            <td>
                                <a href="{{ route('projects.experiments.show', [$project, $study->id]) }}"
                                   class="fw-semibold text-decoration-none">
                                    <i class="fas fa-flask text-muted me-1"></i>{{ $study->name }}
                                </a>

                                @if(filled($study->description ?? null))
                                    <div class="text-muted text-truncate" style="font-size:.78rem; max-width:360px;">
                                        {{ $study->description }}
                                    </div>
                                @elseif(filled($study->summary ?? null))
                                    <div class="text-muted text-truncate" style="font-size:.78rem; max-width:360px;">
                                        {{ $study->summary }}
                                    </div>
                                @endif
                            </td>

                            <td class="text-end">{{ number_format($study->files_count) }}</td>
                            <td class="text-end">{{ number_format($study->entities_count) }}</td>
                            <td class="text-end">{{ number_format($study->activities_count) }}</td>
                            <td class="text-end">{{ number_format($study->datasets_count) }}</td>
                            <td class="text-muted" style="font-size:.82rem;">
                                {{ $study->updated_at?->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
