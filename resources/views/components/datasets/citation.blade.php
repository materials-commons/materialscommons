<div class="row">
    <div class="col-7">
        {{$dataset->owner->name}}, et al. "{{$dataset->name}}", Materials Commons, dataset
        ({{$dataset->published_at->year}}). doi:{{Illuminate\Support\Str::of($dataset->doi)->after('doi:')->trim()}}
    </div>
</div>