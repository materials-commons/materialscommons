<div>
    @include('partials.search._results-toolbar', [
        'heading' => 'Search published data',
        'subheading' => 'Find published datasets and public communities.',
        'query' => $query,
    ])

    @include('partials.search._result-type-tabs', [
        'activeType' => $type,
        'types' => [
            'all' => 'All',
            'datasets' => 'Datasets',
            'communities' => 'Communities',
        ],
    ])

    @if(blank($query))
        <div class="alert alert-light border">
            Enter a search term to find published data.
        </div>
    @elseif($results->isEmpty())
        <div class="alert alert-light border">
            No published results found for <strong>{{ $query }}</strong>.
        </div>
    @else
        @include('partials.search._result-groups', [
            'groups' => $results,
            'compact' => false,
            'showViewTypeLinks' => true,
        ])
    @endif
</div>
