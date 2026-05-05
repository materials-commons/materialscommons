@props([
    'datasets' => collect(),
])

@php
    $datasets = collect($datasets);
    $filterId = 'my-research-dataset-filter-' . uniqid();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-database me-1"></i>Datasets
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Published, draft, owned, and authored datasets with metadata and engagement indicators.
                </p>
            </div>

            <div class="btn-group btn-group-sm flex-wrap" id="{{ $filterId }}" role="group" aria-label="Dataset filters">
                <button type="button" class="btn btn-outline-secondary active" data-dataset-filter="all">All</button>
                <button type="button" class="btn btn-outline-danger" data-dataset-filter="missing-license">Missing license</button>
                <button type="button" class="btn btn-outline-danger" data-dataset-filter="missing-doi">Missing DOI</button>
                <button type="button" class="btn btn-outline-warning" data-dataset-filter="unpublished">Unpublished</button>
                <button type="button" class="btn btn-outline-warning" data-dataset-filter="draft">Draft</button>
                <button type="button" class="btn btn-outline-danger" data-dataset-filter="needs-metadata">Needs metadata</button>
                <button type="button" class="btn btn-outline-info" data-dataset-filter="has-engagement">Has views/downloads</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>License</th>
                    <th>DOI</th>
                    <th>Published</th>
                    <th>Views</th>
                    <th>Downloads</th>
                    <th>Project</th>
                    <th>Last Updated</th>
                    <th>Metadata</th>
                </tr>
                </thead>
                <tbody>
                @forelse($datasets as $dataset)
                    @php
                        $isPublished = filled($dataset->published_at ?? null);
                        $isDraft = !$isPublished;
                        $hasLicense = filled($dataset->license ?? null);
                        $hasDoi = filled($dataset->doi ?? null);
                        $hasDescription = filled($dataset->description ?? null);
                        $hasAuthors = filled($dataset->ds_authors ?? null);
                        $hasEngagement = (int) ($dataset->views_count ?? 0) > 0 || (int) ($dataset->downloads_count ?? 0) > 0;

                        $missing = collect();

                        if (!$hasLicense) {
                            $missing->push('license');
                        }

                        if (!$hasDoi) {
                            $missing->push('DOI');
                        }

                        if (!$hasDescription) {
                            $missing->push('description');
                        }

                        if (!$hasAuthors) {
                            $missing->push('authors');
                        }

                        if (!$isPublished) {
                            $missing->push('publication');
                        }

                        $metadataFields = [
                            filled($dataset->name ?? null),
                            $hasLicense,
                            $hasDoi,
                            $hasDescription,
                            $hasAuthors,
                            $isPublished,
                        ];

                        $metadataCompleteness = round(collect($metadataFields)->filter()->count() / count($metadataFields) * 100);

                        $filters = collect(['all']);

                        if (!$hasLicense) {
                            $filters->push('missing-license');
                        }

                        if (!$hasDoi) {
                            $filters->push('missing-doi');
                        }

                        if (!$isPublished) {
                            $filters->push('unpublished');
                            $filters->push('draft');
                        }

                        if ($missing->count() > 0) {
                            $filters->push('needs-metadata');
                        }

                        if ($hasEngagement) {
                            $filters->push('has-engagement');
                        }
                    @endphp

                    <tr data-dataset-filters="{{ $filters->implode(' ') }}">
                        <td style="min-width:220px;">
                            <a href="{{ route('projects.datasets.show', [$dataset->project_id, $dataset->id]) }}"
                               class="text-decoration-none fw-semibold">
                                {{ $dataset->name }}
                            </a>

                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @if($isPublished)
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-warning">Draft</span>
                                @endif

                                @if((int) ($dataset->owner_id ?? 0) === auth()->id())
                                    <span class="badge text-bg-primary">Owner</span>
                                @else
                                    <span class="badge text-bg-info">Author</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($hasLicense)
                                <span class="text-muted">{{ $dataset->license }}</span>
                            @else
                                <span class="badge text-bg-danger">Missing</span>
                            @endif
                        </td>

                        <td>
                            @if($hasDoi)
                                <span class="text-muted">{{ $dataset->doi }}</span>
                            @else
                                <span class="badge text-bg-danger">Missing</span>
                            @endif
                        </td>

                        <td>
                            @if($isPublished)
                                <span title="{{ optional($dataset->published_at)->format('M j, Y g:i A') }}">
                                    {{ optional($dataset->published_at)->format('M j, Y') }}
                                </span>
                            @else
                                <span class="text-muted">Unpublished</span>
                            @endif
                        </td>

                        <td>{{ number_format($dataset->views_count ?? 0) }}</td>
                        <td>{{ number_format($dataset->downloads_count ?? 0) }}</td>

                        <td>
                            @if($dataset->project)
                                <a href="{{ route('projects.show', [$dataset->project->id]) }}"
                                   class="text-decoration-none">
                                    {{ $dataset->project->name }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            <span title="{{ optional($dataset->updated_at)->format('M j, Y g:i A') }}">
                                {{ optional($dataset->updated_at)->diffForHumans() }}
                            </span>
                        </td>

                        <td style="min-width:180px;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar {{ $metadataCompleteness < 70 ? 'bg-danger' : 'bg-success' }}"
                                         role="progressbar"
                                         style="width: {{ $metadataCompleteness }}%;"
                                         aria-valuenow="{{ $metadataCompleteness }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="text-muted" style="font-size:.75rem;">{{ $metadataCompleteness }}%</span>
                            </div>

                            @if($missing->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($missing->take(3) as $field)
                                        <span class="badge text-bg-light border text-muted">
                                            Missing {{ $field }}
                                        </span>
                                    @endforeach

                                    @if($missing->count() > 3)
                                        <span class="badge text-bg-light border text-muted">
                                            +{{ $missing->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="badge text-bg-success">Complete</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted text-center py-4">
                            No datasets found.
                        </td>
                    </tr>
                @endforelse

                <tr class="d-none" data-empty-filter-row>
                    <td colspan="9" class="text-muted text-center py-4">
                        No datasets match this filter.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const filterGroup = document.getElementById(@json($filterId));

            if (!filterGroup) {
                return;
            }

            const rows = Array.from(document.querySelectorAll('tr[data-dataset-filters]'));
            const emptyRow = document.querySelector('tr[data-empty-filter-row]');

            filterGroup.querySelectorAll('[data-dataset-filter]').forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.dataset.datasetFilter;

                    filterGroup.querySelectorAll('[data-dataset-filter]').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    this.classList.add('active');

                    let visibleCount = 0;

                    rows.forEach(row => {
                        const filters = (row.dataset.datasetFilters || '').split(' ');
                        const visible = filters.includes(filter);

                        row.classList.toggle('d-none', !visible);

                        if (visible) {
                            visibleCount++;
                        }
                    });

                    if (emptyRow) {
                        emptyRow.classList.toggle('d-none', visibleCount !== 0);
                    }
                });
            });
        })();
    </script>
@endpush
