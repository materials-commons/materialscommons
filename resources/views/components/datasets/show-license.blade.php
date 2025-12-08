@if(!blank($dataset->license))
    <span class="ms-3 fs-10 grey-5">
        License: <a href="{{$licenseUrl()}}" target="_blank">
            <i class="fa fas fa-fw fa-external-link-alt"></i> {{$dataset->license}}
        </a>
    </span>
@else
    <span class="ms-3 fs-10 grey-5">License: No license</span>
@endif
