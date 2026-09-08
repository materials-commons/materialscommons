@props([
    'project',
    'metrics' => [],
])

@php
    $items = collect($metrics['datasetCoverageItems'] ?? []);
    $authors = collect($metrics['datasetAuthors'] ?? [])->take(8);
    $totalDatasets = max(1, collect($metrics['datasets'] ?? [])->count());
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-user-edit me-1"></i>Dataset Authors
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Author metadata from datasets in this project.
                </p>
            </div>

            <span class="badge text-bg-success">
                {{ number_format((int) ($metrics['datasetAuthorCount'] ?? 0)) }} authors
            </span>
        </div>

        @foreach($items as $item)
            <x-projects.research-overview.analytics.metric-row
                :label="$item['label']"
                :value="$item['value']"
                :total="$totalDatasets"
                :color="$item['color']"
                :hint="$item['hint']"
            />
        @endforeach

        <div class="border rounded p-2 bg-light">
            <div class="text-muted small fw-semibold mb-1">Top Dataset Authors</div>

            @forelse($authors as $author)
                <div class="d-flex align-items-start justify-content-between gap-2 py-1">
                    <div class="min-width-0">
                        <div class="fw-semibold text-break" style="font-size:.85rem;">
                            {{ $author['name'] }}
                        </div>

                        @if(filled($author['affiliations'] ?? null))
                            <div class="text-muted text-break" style="font-size:.72rem;">
                                {{ $author['affiliations'] }}
                            </div>
                        @endif
                    </div>

                    <span class="badge text-bg-light border flex-shrink-0">
                        {{ number_format((int) ($author['dataset_count'] ?? 0)) }}
                    </span>
                </div>
            @empty
                <div class="text-muted small">
                    No dataset authors recorded yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
