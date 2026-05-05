@props([
    'collaborators' => collect(),
])

@php
    $collaborators = collect($collaborators);
    $filterId = 'my-research-collaborators-filter-' . uniqid();
    $emptyRowId = 'my-research-collaborators-empty-' . uniqid();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-users me-1"></i>Collaborators
                </h6>
                <p class="text-muted mb-0" style="font-size:.85rem;">
                    Co-authors, project collaborators, team roles, private access, and linked research outputs.
                </p>
            </div>

            <div class="btn-group btn-group-sm flex-wrap" id="{{ $filterId }}" role="group" aria-label="Collaborator filters">
                <button type="button" class="btn btn-outline-secondary active" data-collaborator-filter="all">All</button>
                <button type="button" class="btn btn-outline-success" data-collaborator-filter="published-coauthor">Published co-authors</button>
                <button type="button" class="btn btn-outline-info" data-collaborator-filter="project-collaborator">Projects</button>
                <button type="button" class="btn btn-outline-secondary" data-collaborator-filter="team-member">Team</button>
                <button type="button" class="btn btn-outline-warning" data-collaborator-filter="frequent">Frequent</button>
                <button type="button" class="btn btn-outline-primary" data-collaborator-filter="private-access">Private access</button>
                <button type="button" class="btn btn-outline-danger" data-collaborator-filter="external">External</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                <tr>
                    <th>Collaborator</th>
                    <th>Relationship</th>
                    <th>Projects</th>
                    <th>Datasets</th>
                    <th>Role / Access</th>
                    <th>Affiliation</th>
                    <th>Collaboration Score</th>
                </tr>
                </thead>
                <tbody>
                @forelse($collaborators as $collaborator)
                    @php
                        $filters = collect(['all']);

                        if ($collaborator['published_dataset_count'] > 0) {
                            $filters->push('published-coauthor');
                        }

                        if ($collaborator['project_count'] > 0) {
                            $filters->push('project-collaborator');
                        }

                        if ($collaborator['roles']->contains('Member') || $collaborator['roles']->contains('Admin')) {
                            $filters->push('team-member');
                        }

                        if ($collaborator['score'] >= 2) {
                            $filters->push('frequent');
                        }

                        if ($collaborator['private_project_count'] > 0) {
                            $filters->push('private-access');
                        }

                        if ($collaborator['is_external']) {
                            $filters->push('external');
                        }
                    @endphp

                    <tr data-collaborator-filters="{{ $filters->implode(' ') }}">
                        <td style="min-width:220px;">
                            <div class="fw-semibold">{{ $collaborator['name'] }}</div>

                            @if(filled($collaborator['email'] ?? null))
                                <div class="text-muted" style="font-size:.8rem;">{{ $collaborator['email'] }}</div>
                            @endif

                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @if($collaborator['is_external'])
                                    <span class="badge text-bg-danger">External author</span>
                                @else
                                    <span class="badge text-bg-primary">MC user</span>
                                @endif

                                @if($collaborator['score'] >= 2)
                                    <span class="badge text-bg-warning">Frequent</span>
                                @endif
                            </div>
                        </td>

                        <td style="min-width:180px;">
                            @foreach($collaborator['relationships'] as $relationship)
                                <div class="text-muted">{{ $relationship }}</div>
                            @endforeach
                        </td>

                        <td style="min-width:220px;">
                            @forelse(collect($collaborator['projects'])->take(3) as $project)
                                <div>
                                    <a href="{{ route('projects.show', [$project['project_id']]) }}"
                                       class="text-decoration-none">
                                        {{ $project['project_name'] }}
                                    </a>

                                    @if(filled($project['role'] ?? null))
                                        <span class="badge text-bg-light border text-muted">{{ $project['role'] }}</span>
                                    @endif
                                </div>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse

                            @if(collect($collaborator['projects'])->count() > 3)
                                <div class="text-muted" style="font-size:.8rem;">
                                    +{{ collect($collaborator['projects'])->count() - 3 }} more
                                </div>
                            @endif
                        </td>

                        <td style="min-width:220px;">
                            @forelse(collect($collaborator['datasets'])->take(3) as $dataset)
                                <div>
                                    <a href="{{ route('projects.datasets.show', [$dataset['dataset_project_id'], $dataset['dataset_id']]) }}"
                                       class="text-decoration-none">
                                        {{ $dataset['dataset_name'] }}
                                    </a>
                                </div>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse

                            @if(collect($collaborator['datasets'])->count() > 3)
                                <div class="text-muted" style="font-size:.8rem;">
                                    +{{ collect($collaborator['datasets'])->count() - 3 }} more
                                </div>
                            @endif
                        </td>

                        <td style="min-width:160px;">
                            <div class="d-flex flex-wrap gap-1">
                                @forelse($collaborator['roles'] as $role)
                                    <span class="badge text-bg-light border text-muted">{{ $role }}</span>
                                @empty
                                    <span class="badge text-bg-light border text-muted">Author</span>
                                @endforelse

                                @if($collaborator['private_project_count'] > 0)
                                    <span class="badge text-bg-info">Private access</span>
                                @endif
                            </div>
                        </td>

                        <td style="min-width:220px;">
                            @if(filled($collaborator['affiliations'] ?? null))
                                <span class="text-muted">{{ $collaborator['affiliations'] }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td style="min-width:160px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-primary"
                                         role="progressbar"
                                         style="width: {{ min(100, $collaborator['score'] * 20) }}%;"
                                         aria-valuenow="{{ $collaborator['score'] }}"
                                         aria-valuemin="0"
                                         aria-valuemax="5">
                                    </div>
                                </div>
                                <span class="text-muted" style="font-size:.75rem;">
                                    {{ number_format($collaborator['score']) }}
                                </span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted text-center py-4">
                            No collaborators found.
                        </td>
                    </tr>
                @endforelse

                <tr id="{{ $emptyRowId }}" class="d-none">
                    <td colspan="7" class="text-muted text-center py-4">
                        No collaborators match this filter.
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
            const emptyRow = document.getElementById(@json($emptyRowId));

            if (!filterGroup) {
                return;
            }

            const rows = Array.from(document.querySelectorAll('tr[data-collaborator-filters]'));

            filterGroup.querySelectorAll('[data-collaborator-filter]').forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.dataset.collaboratorFilter;

                    filterGroup.querySelectorAll('[data-collaborator-filter]').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    this.classList.add('active');

                    let visibleCount = 0;

                    rows.forEach(row => {
                        const filters = (row.dataset.collaboratorFilters || '').split(' ');
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
