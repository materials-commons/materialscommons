@props([
    'datasets' => collect(),
    'ownedCount' => 0,
])

<div class="tab-pane fade show active" id="tab-owned">
    @if($ownedCount === 0)
        <p class="text-muted">No published datasets owned by this author.</p>
    @else
        <table id="owned-datasets-table" class="table table-hover align-middle" style="width:100%">
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
                    <td>
                        <a href="{{ route('public.datasets.show', $ds) }}" class="fw-semibold">
                            {{ $ds->name }}
                        </a>
                    </td>

                    <td class="text-muted small">
                        {{ $ds->published_at ? $ds->published_at->format('M j, Y') : '—' }}
                    </td>

                    <td>{{ $ds->published_at ? $ds->published_at->timestamp : 0 }}</td>
                    <td>{{ number_format($ds->views_count) }}</td>
                    <td>{{ number_format($ds->downloads_count) }}</td>

                    <td>
                        @foreach($ds->tags as $tag)
                            <a href="{{ route('public.tags.search', ['tag' => $tag->name]) }}"
                               class="badge text-bg-success text-decoration-none me-1"
                               style="font-size:.72rem;">{{ $tag->name }}</a>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
