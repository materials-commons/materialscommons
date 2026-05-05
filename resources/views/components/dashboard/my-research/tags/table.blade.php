@props([
    'myTags' => collect(),
    'listedTags' => collect(),
])

@php
    $myTags = collect($myTags);
    $listedTags = collect($listedTags);

    $myTagMap = $myTags->keyBy(fn($tag) => mb_strtolower($tag['tag']));
    $listedTagMap = $listedTags->keyBy(fn($tag) => mb_strtolower($tag['tag']));

    $rows = $myTagMap
        ->keys()
        ->merge($listedTagMap->keys())
        ->unique()
        ->map(function ($key) use ($myTagMap, $listedTagMap) {
            $myTag = $myTagMap->get($key);
            $listedTag = $listedTagMap->get($key);

            return [
                'tag' => $myTag['tag'] ?? $listedTag['tag'],
                'my_count' => $myTag['count'] ?? 0,
                'listed_count' => $listedTag['count'] ?? 0,
                'total_count' => ($myTag['count'] ?? 0) + ($listedTag['count'] ?? 0),
                'views_count' => ($myTag['views_count'] ?? 0) + ($listedTag['views_count'] ?? 0),
                'downloads_count' => ($myTag['downloads_count'] ?? 0) + ($listedTag['downloads_count'] ?? 0),
            ];
        })
        ->sortByDesc('total_count')
        ->values();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="card-title text-muted mb-0">
                <i class="fas fa-table me-1"></i>Tag Details
            </h6>
            <span class="text-muted small">{{ number_format($rows->count()) }} unique tags</span>
        </div>

        @if($rows->isEmpty())
            <p class="text-muted mb-0">No tags found.</p>
        @else
            <div class="table-responsive">
                <table id="my-research-tags-table" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th>Tag</th>
                        <th>My Datasets</th>
                        <th>Listed In</th>
                        <th>Total Datasets</th>
                        <th>Views</th>
                        <th>Downloads</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td>
                                <a href="{{ route('public.tags.search', ['tag' => $row['tag']]) }}"
                                   class="badge text-bg-success text-decoration-none"
                                   style="font-size:.78rem; font-weight:normal;">
                                    {{ $row['tag'] }}
                                </a>
                            </td>
                            <td>{{ number_format($row['my_count']) }}</td>
                            <td>{{ number_format($row['listed_count']) }}</td>
                            <td>{{ number_format($row['total_count']) }}</td>
                            <td>{{ number_format($row['views_count']) }}</td>
                            <td>{{ number_format($row['downloads_count']) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @push('scripts')
                <script>
                    $(document).ready(() => {
                        $('#my-research-tags-table').DataTable({
                            pageLength: 25,
                            stateSave: true,
                            order: [[3, 'desc']],
                            columnDefs: [
                                {targets: [1, 2, 3, 4, 5], type: 'num'},
                            ],
                        });
                    });
                </script>
            @endpush
        @endif
    </div>
</div>
