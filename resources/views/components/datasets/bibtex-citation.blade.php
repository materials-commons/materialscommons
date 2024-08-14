<div>
    <H5>Bibtex</H5>
    <pre>
    @misc&#123;{{$id}},
        doi = &#123;{{Illuminate\Support\Str::of($dataset->doi)->after('doi:')->trim()}}&#125;,
        url = &#123;{{route('public.datasets.show', [$dataset])}}&#125;,
        author = &#123;&#123;{{$dataset->owner->name}}&#125;&#125;,
        title = &#123;{{$dataset->name}}&#125;,
        publisher = {Materials Commons},
        year = &#123;{{$dataset->published_at->year}}&#125;
    }
</pre>
</div>