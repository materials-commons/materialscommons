<div>
    <li class="nav-item mt-2">
        <span class="ms-5 italic font-bold">
            Beta
            <a href="#" data-bs-toggle="tooltip" title="This is a beta for a new way to interact with Globus.
            If you run into any issues or have feedback please send an email to materials-commons-help@umich.edu.
            ">
                <i class="fa-fw fas fa-question-circle"></i>
            </a>
        </span>
    </li>

    <li class="nav-item mt-2 ms-3">
        <span class="ms-5">
            <i class="fa-fw fas fa-globe me-2"></i>
            Globus
        </span>
    </li>

    @if(is_null($globusTransfer))
        <li class="nav-item ms-3">
            <a class="nav-link fs-11 ms-5 {{setActiveNavByName('projects.globus.start')}}"
               href="{{route('projects.globus.start', [$project])}}">
                <i class="fa-fw fas fa-play me-2"></i>
                Start
            </a>
        </li>

        <li class="nav-item ms-3">
            <a class="nav-link fs-11 ms-5 nav-disabled" href="#">
                <i class="fa-fw fas fa-check me-2"></i>
                Done
            </a>
        </li>

        {{--            <li class="nav-item ms-3">--}}
        {{--                <a class="nav-link fs-11 ms-5 nav-disabled" href="#">--}}
        {{--                    <i class="fa-fw fas fa-eye me-2"></i>--}}
        {{--                    Monitor--}}
        {{--                </a>--}}
        {{--            </li>--}}
    @else
        <li class="nav-item ms-3">
            <a class="nav-link fs-11 ms-5" href="{{$globusTransfer->globus_url}}" target="_blank">
                <i class="fa-fw fas fa-arrow-alt-circle-right me-2"></i>
                Use
            </a>
        </li>

        <li class="nav-item ms-3">
            <a class="nav-link fs-11 ms-5 {{setActiveNavByName('projects.globus.close')}}"
               href="{{route('projects.globus.close', [$project])}}">
                <i class="fa-fw fas fa-check me-2"></i>
                Done
            </a>
        </li>

        {{--            <li class="nav-item ms-3">--}}
        {{--                <a class="nav-link fs-11 ms-5 {{setActiveNavByName('projects.globus.monitor')}}"--}}
        {{--                   href="{{route('projects.globus.monitor', [$project])}}">--}}
        {{--                    <i class="fa-fw fas fa-eye me-2"></i>--}}
        {{--                    Monitor--}}
        {{--                </a>--}}
        {{--            </li>--}}
    @endif
</div>
