@if(!blank($doi))
    <span class="ml-3 fs-9 grey-5">
        DOI: <a href="https://doi.org/{{Illuminate\Support\Str::of($doi)->after('doi:')->trim()}}" target="_blank">
            <i class="fa fas fa-fw fa-external-link-alt"></i> {{$doi}}
        </a>
    </span>
@endif