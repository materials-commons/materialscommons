@props([
    'datasets' => collect(),
])

@php
    $datasets = collect($datasets)->take(8);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted">
            <i class="fas fa-exclamation-triangle me-1"></i>Datasets Needing Attention
        </h6>

        @forelse($datasets as $dataset)
            @php
                $issues = collect();

                if (blank($dataset->published_at ?? null)) {
                    $issues->push('Unpublished');
                }

                if (blank($dataset->license ?? null)) {
                    $issues->push('Missing license');
                }

                if (blank($dataset->doi ?? null)) {
                    $issues->push('Missing DOI');
                }

                if (blank($dataset->description ?? null)) {
                    $issues->push('Missing description');
                }

                if (blank($dataset->ds_authors ?? null)) {
                    $issues->push('Missing authors');
                }
            @endphp

            <div class="border rounded p-2 mb-2">
                <div class="d-flex justify-content-between gap-2">
                    <div class="min-w-0">
                        <a href="{{ route('projects.datasets.show', [$dataset->project_id, $dataset->id]) }}"
                           class="fw-semibold text-decoration-none text-truncate d-block">
                            {{ $dataset->name }}
                        </a>

                        <div class="text-muted" style="font-size:.75rem;">
                            {{ $dataset->project->name ?? 'No project' }}
                        </div>
                    </div>

                    <span class="badge text-bg-danger align-self-start">
                        {{ $issues->count() }}
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-1 mt-2">
                    @foreach($issues->take(4) as $issue)
                        <span class="badge text-bg-light border text-muted">{{ $issue }}</span>
                    @endforeach

                    @if($issues->count() > 4)
                        <span class="badge text-bg-light border text-muted">
                            +{{ $issues->count() - 4 }} more
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                <div class="fw-semibold">No dataset attention items</div>
                <div style="font-size:.85rem;">Your dataset metadata is in good shape.</div>
            </div>
        @endforelse
    </div>
</div>
