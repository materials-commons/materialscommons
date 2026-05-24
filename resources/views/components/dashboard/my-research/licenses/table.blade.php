{{-- resources/views/components/dashboard/my-research/licenses/table.blade.php --}}
@props([
    'licenseRows' => collect(),
])

@php
    $licenseRows = collect($licenseRows);
    $filterId = 'my-research-license-filter-' . uniqid();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-balance-scale me-1"></i>Dataset Licenses
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Review dataset licenses with filters for missing, custom, public, and draft license issues.
                </p>
            </div>

            <div class="btn-group btn-group-sm flex-wrap" id="{{ $filterId }}" role="group" aria-label="License filters">
                <button type="button" class="btn btn-outline-secondary active" data-license-filter="all">All</button>
                <button type="button" class="btn btn-outline-danger" data-license-filter="missing-license">Missing license</button>
                <button type="button" class="btn btn-outline-warning" data-license-filter="custom-unknown">Custom / unknown</button>
                <button type="button" class="btn btn-outline-danger" data-license-filter="public-missing">Public missing</button>
                <button type="button" class="btn btn-outline-secondary" data-license-filter="draft-missing">Draft missing</button>
                <button type="button" class="btn btn-outline-success" data-license-filter="licensed">Licensed</button>
                <button type="button" class="btn btn-outline-info" data-license-filter="published">Published</button>
                <button type="button" class="btn btn-outline-warning" data-license-filter="draft">Draft</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                <tr>
                    <th>Dataset</th>
                    <th>Status</th>
                    <th>License</th>
                    <th>Project</th>
                    <th>Issues</th>
                    <th>Last Updated</th>
                </tr>
                </thead>
                <tbody>
                @forelse($licenseRows as $row)
                    @php
                        $dataset = $row['dataset'];
                        $isPublished = $row['status'] === 'Published';

                        $filters = collect(['all']);

                        if ($row['has_license']) {
                            $filters->push('licensed');
                        } else {
                            $filters->push('missing-license');
                        }

                        if ($row['is_custom_unknown']) {
                            $filters->push('custom-unknown');
                        }

                        if ($isPublished) {
                            $filters->push('published');

                            if (!$row['has_license']) {
                                $filters->push('public-missing');
                            }
                        } else {
                            $filters->push('draft');

                            if (!$row['has_license']) {
                                $filters->push('draft-missing');
                            }
                        }
                    @endphp

                    <tr data-license-filters="{{ $filters->implode(' ') }}">
                        <td style="min-width:240px;">
                            <a href="{{ route('projects.datasets.show', [$dataset->project_id, $dataset->id]) }}"
                               class="text-decoration-none fw-semibold">
                                {{ $dataset->name }}
                            </a>

                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @if((int) ($dataset->owner_id ?? 0) === auth()->id())
                                    <span class="badge text-bg-primary">Owner</span>
                                @else
                                    <span class="badge text-bg-info">Author</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($isPublished)
                                <span class="badge text-bg-success">Published</span>
                            @else
                                <span class="badge text-bg-warning">Draft</span>
                            @endif
                        </td>

                        <td style="min-width:180px;">
                            @if($row['has_license'])
                                <span class="text-muted">{{ $row['license'] }}</span>
                            @else
                                <span class="badge text-bg-danger">Missing</span>
                            @endif
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

                        <td style="min-width:220px;">
                            @if($row['issues']->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($row['issues'] as $issue)
                                        <span class="badge text-bg-light border text-muted">{{ $issue }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="badge text-bg-success">No license issues</span>
                            @endif
                        </td>

                        <td>
                            <span title="{{ optional($dataset->updated_at)->format('M j, Y g:i A') }}">
                                {{ optional($dataset->updated_at)->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted text-center py-4">
                            No datasets found.
                        </td>
                    </tr>
                @endforelse

                <tr class="d-none" data-empty-license-filter-row>
                    <td colspan="6" class="text-muted text-center py-4">
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

            const rows = Array.from(document.querySelectorAll('tr[data-license-filters]'));
            const emptyRow = document.querySelector('tr[data-empty-license-filter-row]');

            filterGroup.querySelectorAll('[data-license-filter]').forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.dataset.licenseFilter;

                    filterGroup.querySelectorAll('[data-license-filter]').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    this.classList.add('active');

                    let visibleCount = 0;

                    rows.forEach(row => {
                        const filters = (row.dataset.licenseFilters || '').split(' ');
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
