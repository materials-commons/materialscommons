@props([
    'datasets' => collect(),
    'ownedCount' => 0,
])

<div class="tab-pane fade show active" id="tab-owned">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 background-white">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                <div>
                    <h6 class="card-title text-muted mb-1">
                        <i class="fas fa-database me-1"></i>Owned Datasets
                    </h6>
                    <p class="text-muted mb-0" style="font-size:.85rem;">
                        Published datasets owned by this author.
                    </p>
                </div>

                <span class="badge text-bg-primary">
                    {{ number_format($ownedCount) }}
                </span>
            </div>

            @if($ownedCount === 0)
                <x-public.author.empty-state
                    icon="fas fa-database"
                    title="No owned datasets"
                    message="This author has not published any datasets they own yet."
                />
            @else
                <div class="table-responsive">
                    <table id="owned-datasets-table" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Published</th>
                            <th>PublishedTS</th>
                            <th>Views</th>
                            <th>Downloads</th>
                            <th>Tags</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($datasets as $ds)
                            <tr>
                                <td style="min-width:260px;">
                                    <a href="{{ route('public.datasets.show', $ds) }}" class="fw-semibold text-decoration-none">
                                        {{ $ds->name }}
                                    </a>
                                </td>

                                <td class="text-muted small">
                                    {{ $ds->published_at ? $ds->published_at->format('M j, Y') : '—' }}
                                </td>

                                <td>{{ $ds->published_at ? $ds->published_at->timestamp : 0 }}</td>
                                <td>{{ number_format($ds->views_count) }}</td>
                                <td>{{ number_format($ds->downloads_count) }}</td>

                                <td style="min-width:220px;">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($ds->tags as $tag)
                                            <a href="{{ route('public.tags.search', ['tag' => $tag->name]) }}"
                                               class="badge text-bg-success text-decoration-none"
                                               style="font-size:.72rem; font-weight:normal;">{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
