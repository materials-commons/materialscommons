@props(['dataset'])

@php
    $doi = !blank($dataset->doi) ? $dataset->doi : $dataset->test_doi;
@endphp

@if(!blank($doi))
    <span class="ms-3 fs-10 grey-5">
        DOI: <a href="https://doi.org/{{Illuminate\Support\Str::of($doi)->after('doi:')->trim()}}" target="_blank">
            <i class="fa fas fa-fw fa-external-link-alt"></i> {{$doi}}
        </a>
    </span>
@endif
