@props([
    'paperRows' => collect(),
    'datasetsWithoutPapers' => collect(),
])

@php
    $paperRows = collect($paperRows);
    $datasetsWithoutPapers = collect($datasetsWithoutPapers);
    $filterId = 'my-research-papers-filter-' . uniqid();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-file-alt me-1"></i>Papers
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Papers, DOI coverage, linked datasets, citation status, setup user, and related projects.
                </p>
            </div>

            <div class="btn-group btn-group-sm flex-wrap" id="{{ $filterId }}" role="group" aria-label="Paper filters">
                <button type="button" class="btn btn-outline-secondary active" data-paper-filter="all">All</button>
                <button type="button" class="btn btn-outline-danger" data-paper-filter="missing-doi">Missing DOI</button>
                <button type="button" class="btn btn-outline-success" data-paper-filter="linked-datasets">Linked datasets</button>
                <button type="button" class="btn btn-outline-warning" data-paper-filter="metadata-incomplete">Metadata incomplete</button>
                <button type="button" class="btn btn-outline-info" data-paper-filter="multi-dataset">Multiple datasets</button>
                <button type="button" class="btn btn-outline-warning" data-paper-filter="dataset-without-paper">Datasets without paper</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                <tr>
                    <th>Paper / Dataset</th>
                    <th>DOI</th>
                    <th>Linked Datasets</th>
                    <th>Projects</th>
                    <th>Setup User</th>
                    <th>Status</th>
                    <th>Updated</th>
                </tr>
                </thead>
                <tbody>
                @forelse($paperRows as $row)
                    @php
                        $paper = $row['paper'];
                        $linkedDatasets = collect($row['datasets']);
                        $missing = collect($row['missing']);
                        $projects = collect($row['projects']);

                        $filters = collect(['all']);

                        if (blank($paper->doi ?? null)) {
                            $filters->push('missing-doi');
                        }

                        if ($linkedDatasets->isNotEmpty()) {
                            $filters->push('linked-datasets');
                        }

                        if ($missing->isNotEmpty()) {
                            $filters->push('metadata-incomplete');
                        }

                        if ($linkedDatasets->count() > 1) {
                            $filters->push('multi-dataset');
                        }
                    @endphp

                    <tr data-paper-filters="{{ $filters->implode(' ') }}">
                        <td style="min-width:260px;">
                            <div class="fw-semibold">{{ $paper->name }}</div>

                            @if(filled($paper->reference ?? null))
                                <div class="text-muted text-truncate" style="font-size:.8rem; max-width:420px;">
                                    {{ $paper->reference }}
                                </div>
                            @endif

                            @if(filled($paper->url ?? null))
                                <a href="{{ $paper->url }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="text-decoration-none"
                                   style="font-size:.8rem;">
                                    <i class="fas fa-external-link-alt me-1"></i>Paper URL
                                </a>
                            @endif
                        </td>

                        <td>
                            @if(filled($paper->doi ?? null))
                                <span class="text-muted">{{ $paper->doi }}</span>
                            @else
                                <span class="badge text-bg-danger">Missing</span>
                            @endif
                        </td>

                        <td style="min-width:220px;">
                            @forelse($linkedDatasets->take(3) as $dataset)
                                <div>
                                    <a href="{{ route('projects.datasets.show', [$dataset->project_id, $dataset->id]) }}"
                                       class="text-decoration-none">
                                        {{ $dataset->name }}
                                    </a>
                                </div>
                            @empty
                                <span class="badge text-bg-warning">No linked dataset</span>
                            @endforelse

                            @if($linkedDatasets->count() > 3)
                                <div class="text-muted" style="font-size:.8rem;">
                                    +{{ $linkedDatasets->count() - 3 }} more
                                </div>
                            @endif
                        </td>

                        <td style="min-width:180px;">
                            @forelse($projects->take(3) as $project)
                                <div class="text-muted">{{ $project }}</div>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse

                            @if($projects->count() > 3)
                                <div class="text-muted" style="font-size:.8rem;">
                                    +{{ $projects->count() - 3 }} more
                                </div>
                            @endif
                        </td>

                        <td>
                            {{ $paper->owner?->name ?? 'Unknown User' }}
                        </td>

                        <td style="min-width:180px;">
                            @if($missing->isEmpty())
                                <span class="badge text-bg-success">Complete</span>
                            @else
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
                            @endif
                        </td>

                        <td>
                            <span title="{{ optional($paper->updated_at)->format('M j, Y g:i A') }}">
                                {{ optional($paper->updated_at)->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted text-center py-4">
                            No papers linked to your datasets yet.
                        </td>
                    </tr>
                @endforelse

                @foreach($datasetsWithoutPapers as $dataset)
                    <tr data-paper-filters="all dataset-without-paper metadata-incomplete">
                        <td style="min-width:260px;">
                            <div class="fw-semibold">
                                <a href="{{ route('projects.datasets.show', [$dataset->project_id, $dataset->id]) }}"
                                   class="text-decoration-none">
                                    {{ $dataset->name }}
                                </a>
                            </div>
                            <div class="text-muted" style="font-size:.8rem;">
                                Dataset without associated paper
                            </div>
                        </td>

                        <td>
                            @if(filled($dataset->doi ?? null))
                                <span class="text-muted">{{ $dataset->doi }}</span>
                            @else
                                <span class="badge text-bg-danger">Dataset DOI missing</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge text-bg-warning">No paper</span>
                        </td>

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
                            <span class="text-muted">—</span>
                        </td>

                        <td>
                            <span class="badge text-bg-warning">Needs citation</span>
                        </td>

                        <td>
                            <span title="{{ optional($dataset->updated_at)->format('M j, Y g:i A') }}">
                                {{ optional($dataset->updated_at)->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                @endforeach

                <tr class="d-none" data-empty-paper-filter-row>
                    <td colspan="7" class="text-muted text-center py-4">
                        No paper records match this filter.
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

            const rows = Array.from(document.querySelectorAll('tr[data-paper-filters]'));
            const emptyRow = document.querySelector('tr[data-empty-paper-filter-row]');

            filterGroup.querySelectorAll('[data-paper-filter]').forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.dataset.paperFilter;

                    filterGroup.querySelectorAll('[data-paper-filter]').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    this.classList.add('active');

                    let visibleCount = 0;

                    rows.forEach(row => {
                        const filters = (row.dataset.paperFilters || '').split(' ');
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
