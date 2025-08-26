<div>
    <H5>Bibtex</H5>
    <pre>
    @misc&#123;{{$id}},
        @if(!blank($dataset->published_at))
            doi = &#123;{{Illuminate\Support\Str::of($dataset->doi)->after('doi:')->trim()}}&#125;,
        @elseif(!blank($dataset->test_published_at))
            doi = &#123;{{Illuminate\Support\Str::of($dataset->test_doi)->after('doi:')->trim()}}&#125;,
        @endif
        url = &#123;{{route('public.datasets.show', [$dataset])}}&#125;,
        author = &#123;&#123;{{$dataset->owner->name}}&#125;&#125;,
        title = &#123;{{$dataset->name}}&#125;,
        publisher = {Materials Commons},
        @if(!blank($dataset->published_at))
            year = &#123;{{$dataset->published_at->year}}&#125;
        @elseif(!blank($dataset->test_published_at))
            year = &#123;{{$dataset->test_published_at->year}}&#125;
        @endif
    }
</pre>
</div>
