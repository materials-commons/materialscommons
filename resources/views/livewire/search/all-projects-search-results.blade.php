<div>
    @include('partials.search._results-toolbar', [
        'heading' => 'Search across projects',
        'subheading' => 'Search across projects you can access.',
        'query' => $query,
    ])

    @include('partials.search._result-type-tabs', [
        'activeType' => $type,
        'types' => [
            'all' => 'All',
            'files' => 'Files',
            'experiments' => 'Experiments',
            'entities' => 'Samples',
            'activities' => 'Processes',
            'datasets' => 'Datasets',
        ],
    ])

    @if(blank($query))
        <div class="alert alert-light border">
            Enter a search term to search across your projects.
        </div>
    @elseif($results->isEmpty())
        <div class="alert alert-light border">
            No project results found for <strong>{{ $query }}</strong>.
        </div>
    @else
        @include('partials.search._result-groups', [
            'groups' => $results,
            'compact' => false,
            'showViewTypeLinks' => true,
        ])
    @endif
</div>
