@if(!blank($license))
    <span class="ml-3 fs-9 grey-5">
        License: <a href="{{$licenseLink}}" target="_blank">
            <i class="fa fas fa-fw fa-external-link-alt"></i> {{$license}}
        </a>
    </span>
@else
    <span class="ml-3 fs-9 grey-5">License: No license</span>
@endif