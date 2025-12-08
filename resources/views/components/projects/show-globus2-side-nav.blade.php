<div>
    <li class="nav-item mt-3">
        <span class="ms-3 fs-11">
            <i class="fa-fw fas fa-globe me-2"></i>
            Beta Globus NG2
        </span>
    </li>

    @if(is_null($globusTransfer))
        <li class="nav-item ms-3">
            <a class="nav-link fs-12 ms-3 {{setActiveNavByName('projects.globus2.start')}}"
               href="{{route('projects.globus2.start', [$project])}}">
                <i class="fa-fw fas fa-play me-2 mb-1 fs-11"></i>
                Start
            </a>
        </li>

        <li class="nav-item ms-3">
            <a class="nav-link fs-12 ms-3 nav-disabled" href="#">
                <i class="fa-fw fas fa-check me-2 mb-1 fs-11"></i>
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
            <a class="nav-link fs-12 ms-3" href="{{$globusTransfer->globus_url}}" target="_blank">
                <i class="fa-fw fas fa-arrow-alt-circle-right me-2 fs-11"></i>
                Use
            </a>
        </li>

        <li class="nav-item ms-3">
            <a class="nav-link fs-12 ms-3 {{setActiveNavByName('projects.globus2.close')}}"
               href="{{route('projects.globus2.close', [$project])}}">
                <i class="fa-fw fas fa-check me-2 fs-11"></i>
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
