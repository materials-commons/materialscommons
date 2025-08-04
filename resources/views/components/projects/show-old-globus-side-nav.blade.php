<div>
    <li class="nav-item">
        <a class="nav-link fs-11 {{setActiveNavByName('projects.globus.uploads.index')}}"
           data-toggle="tooltip" title="Upload data to your project using Globus."
           href="{{route('projects.globus.uploads.index', [$project])}}">
            <i class="fa-fw fas fa-cloud-upload-alt mr-2 mb-1"></i>
            Globus Upload
        </a>
    </li>

    {{--    @if(is_null($globusUpload))--}}
    {{--        <li class="nav-item ml-3">--}}
    {{--            <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.globus.start')}}"--}}
    {{--               href="{{route('projects.globus.start', [$project])}}">--}}
    {{--                <i class="fa-fw fas fa-play mr-2"></i>--}}
    {{--                Start--}}
    {{--            </a>--}}
    {{--        </li>--}}
    {{--    @else--}}
    {{--        <li class="nav-item ml-3">--}}
    {{--            <a class="nav-link fs-11 ml-5" href="{{$globusUpload->globus_url}}" target="_blank">--}}
    {{--                <i class="fa-fw fas fa-arrow-alt-circle-right mr-2"></i>--}}
    {{--                Use--}}
    {{--            </a>--}}
    {{--        </li>--}}

    {{--        <li class="nav-item ml-3">--}}
    {{--            <a class="nav-link fs-11 ml-5" href="#">--}}
    {{--                <i class="fa-fw fas fa-check mr-2"></i>--}}
    {{--                Done--}}
    {{--            </a>--}}
    {{--        </li>--}}
    {{--    @endif--}}


    <li class="nav-item">
        <a class="nav-link fs-11 {{setActiveNavByName('projects.globus.downloads.index')}}"
           data-toggle="tooltip" title="Download your projects files using Globus."
           href="{{route('projects.globus.downloads.index', [$project])}}">
            <i class="fa-fw fas fa-cloud-download-alt mr-2 mb-1"></i>
            Globus Download
        </a>
    </li>

    {{--    @if(is_null($globusDownload))--}}
    {{--        <li class="nav-item ml-3">--}}
    {{--            <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.globus.start')}}"--}}
    {{--               href="{{route('projects.globus.start', [$project])}}">--}}
    {{--                <i class="fa-fw fas fa-play mr-2"></i>--}}
    {{--                Start--}}
    {{--            </a>--}}
    {{--        </li>--}}
    {{--    @else--}}
    {{--        <li class="nav-item ml-3">--}}
    {{--            <a class="nav-link fs-11 ml-5" href="{{$globusDownload->globus_url}}" target="_blank">--}}
    {{--                <i class="fa-fw fas fa-arrow-alt-circle-right mr-2"></i>--}}
    {{--                Use--}}
    {{--            </a>--}}
    {{--        </li>--}}

    {{--        <li class="nav-item ml-3">--}}
    {{--            <a class="nav-link fs-11 ml-5" href="#">--}}
    {{--                <i class="fa-fw fas fa-check mr-2"></i>--}}
    {{--                Done--}}
    {{--            </a>--}}
    {{--        </li>--}}
    {{--    @endif--}}

</div>
