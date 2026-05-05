@props([
    'datasets' => collect(),
    'includedCount' => 0,
])

<div class="tab-pane fade" id="tab-included">
    @if($includedCount === 0)
        <p class="text-muted">This author does not appear in other published datasets.</p>
    @else
        <table id="included-datasets-table" class="table table-hover align-middle" style="width:100%">
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
                    <td>
                        <a href="{{ route('public.datasets.show', $ds) }}" class="fw-semibold">
                            {{ $ds->name }}
                        </a>
                    </td>

                    <td class="text-muted small">{{ $ds->owner->name ?? '—' }}</td>

                    <td class="text-muted small">
                        {{ $ds->published_at ? $ds->published_at->format('M j, Y') : '—' }}
                    </td>

                    <td>{{ $ds->published_at ? $ds->published_at->timestamp : 0 }}</td>

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
