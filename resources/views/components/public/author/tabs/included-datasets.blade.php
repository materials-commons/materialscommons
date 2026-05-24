@props([
    'datasets' => collect(),
    'includedCount' => 0,
])

<div class="tab-pane fade" id="tab-included">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 background-white">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                <div>
                    <h6 class="card-title text-muted mb-1">
                        <i class="fas fa-list me-1"></i>Included In
                    </h6>
                    <p class="text-muted mb-0" style="font-size:.85rem;">
                        Published datasets where this author is listed as an author.
                    </p>
                </div>

                <span class="badge text-bg-info">
                    {{ number_format($includedCount) }}
                </span>
            </div>

            @if($includedCount === 0)
                <x-public.author.empty-state
                    icon="fas fa-list"
                    title="No included datasets"
                    message="This author does not appear in other published datasets."
                />
            @else
                <div class="table-responsive">
                    <table id="included-datasets-table" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Owner</th>
                            <th>Published</th>
                            <th>PublishedTS</th>
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

                                <td class="text-muted small">{{ $ds->owner->name ?? '—' }}</td>

                                <td class="text-muted small">
                                    {{ $ds->published_at ? $ds->published_at->format('M j, Y') : '—' }}
                                </td>

                                <td>{{ $ds->published_at ? $ds->published_at->timestamp : 0 }}</td>

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
