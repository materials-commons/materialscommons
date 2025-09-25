<div class="row">
    <div class="col-7">
        {{$dataset->owner->name}}, et al. "{{$dataset->name}}", Materials Commons, dataset
        @if (!blank($dataset->published_at))
        ({{$dataset->published_at->year}}). doi:{{Illuminate\Support\Str::of($dataset->doi)->after('doi:')->trim()}}
        @elseif(!blank($dataset->test_published_at))
            ({{$dataset->test_published_at->year}}). doi:{{Illuminate\Support\Str::of($dataset->test_doi)->after('doi:')->trim()}}
        @endif
    </div>
</div>
