@props([
    'communityRows' => collect(),
])

@php
    $communityRows = collect($communityRows);
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="card-title text-muted mb-0">
                <i class="fas fa-table me-1"></i>Community Details
            </h6>
            <span class="text-muted small">{{ number_format($communityRows->count()) }} communities</span>
        </div>

        @if($communityRows->isEmpty())
            <p class="text-muted mb-0">No communities found.</p>
        @else
            <div class="table-responsive">
                <table id="my-research-communities-table" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th>Community</th>
                        <th>Role</th>
                        <th>Organizer</th>
                        <th>Datasets</th>
                        <th>Views</th>
                        <th>Downloads</th>
                        <th>Topics</th>
                        <th>Contributors</th>
                        <th>Resources</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($communityRows as $row)
                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    <a href="{{ $row['url'] }}" class="text-decoration-none">
                                        {{ $row['name'] }}
                                    </a>
                                </div>

                                @if(filled($row['summary']))
                                    <div class="text-muted small" style="max-width:320px;">
                                        {{ \Illuminate\Support\Str::limit($row['summary'], 90) }}
                                    </div>
                                @endif

                                <div class="mt-1">
                                    <a href="{{ $row['datasets_url'] }}"
                                       class="badge text-bg-light border text-dark text-decoration-none"
                                       style="font-weight:normal;">
                                        <i class="fas fa-database me-1"></i>Datasets
                                    </a>
                                </div>
                            </td>

                            <td>
                                @if($row['is_owner'])
                                    <span class="badge text-bg-success">Organizer</span>
                                @else
                                    <span class="badge text-bg-info">Participant</span>
                                @endif

                                @if($row['is_public'])
                                    <span class="badge text-bg-light border text-muted mt-1">Public</span>
                                @endif
                            </td>

                            <td>
                                @if($row['owner_url'])
                                    <a href="{{ $row['owner_url'] }}" class="text-decoration-none">
                                        {{ $row['owner_name'] }}
                                    </a>
                                @else
                                    {{ $row['owner_name'] ?? '—' }}
                                @endif

                                @if(filled($row['owner_affiliations']))
                                    <div class="text-muted small">
                                        {{ \Illuminate\Support\Str::limit($row['owner_affiliations'], 60) }}
                                    </div>
                                @endif
                            </td>

                            <td>{{ number_format($row['dataset_count']) }}</td>
                            <td>{{ number_format($row['views_count']) }}</td>
                            <td>{{ number_format($row['downloads_count']) }}</td>

                            <td>
                                @if(!empty($row['tags']))
                                    <div class="d-flex flex-wrap gap-1" style="max-width:260px;">
                                        @foreach(array_slice($row['tags'], 0, 5, true) as $tag => $count)
                                            <a href="{{ route('public.communities.search.tag', [$row['community'], 'tag' => $tag]) }}"
                                               class="badge text-bg-success text-decoration-none"
                                               style="font-size:.68rem; font-weight:normal;">
                                                {{ $tag }}
                                                <span class="ms-1 opacity-75">{{ $count }}</span>
                                            </a>
                                        @endforeach

                                        @if(count($row['tags']) > 5)
                                            <span class="badge text-bg-light border text-muted">
                                                +{{ count($row['tags']) - 5 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if(!empty($row['contributors']))
                                    <div class="d-flex flex-wrap gap-1" style="max-width:260px;">
                                        @foreach(array_slice($row['contributors'], 0, 4, true) as $name => $affiliation)
                                            <a href="{{ route('public.communities.search.author', [$row['community'], 'author' => $name]) }}"
                                               class="badge text-bg-light border text-dark text-decoration-none"
                                               style="font-size:.68rem; font-weight:normal;">
                                                {{ $name }}
                                            </a>
                                        @endforeach

                                        @if(count($row['contributors']) > 4)
                                            <span class="badge text-bg-light border text-muted">
                                                +{{ count($row['contributors']) - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge text-bg-light border text-dark">
                                    <i class="fas fa-link me-1"></i>{{ number_format($row['links_count']) }}
                                </span>
                                <span class="badge text-bg-light border text-dark">
                                    <i class="fas fa-file me-1"></i>{{ number_format($row['files_count']) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @push('scripts')
                <script>
                    $(document).ready(() => {
                        $('#my-research-communities-table').DataTable({
                            pageLength: 25,
                            stateSave: true,
                            order: [[3, 'desc']],
                            columnDefs: [
                                {targets: [3, 4, 5], type: 'num'},
                                {targets: [6, 7, 8], orderable: false},
                            ],
                        });
                    });
                </script>
            @endpush
        @endif
    </div>
</div>
