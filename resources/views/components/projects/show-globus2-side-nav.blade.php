<div>
    <li class="nav-item mt-2">
        <span class="ml-5 italic font-bold">
            Beta NG2
            <a href="#" data-toggle="tooltip" title="This is a beta for a new way to interact with Globus.
            If you run into any issues or have feedback please send an email to materials-commons-help@umich.edu.
            ">
                <i class="fa-fw fas fa-question-circle"></i>
            </a>
        </span>
    </li>

    <li class="nav-item mt-2 ml-3">
        <span class="ml-5">
            <i class="fa-fw fas fa-globe mr-2"></i>
            Globus
        </span>
    </li>

    @if(is_null($globusTransfer))
        <li class="nav-item ml-3">
            <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.globus2.start')}}"
               href="{{route('projects.globus2.start', [$project])}}">
                <i class="fa-fw fas fa-play mr-2"></i>
                Start
            </a>
        </li>

        <li class="nav-item ml-3">
            <a class="nav-link fs-11 ml-5 nav-disabled" href="#">
                <i class="fa-fw fas fa-check mr-2"></i>
                Done
            </a>
        </li>

        {{--            <li class="nav-item ml-3">--}}
        {{--                <a class="nav-link fs-11 ml-5 nav-disabled" href="#">--}}
        {{--                    <i class="fa-fw fas fa-eye mr-2"></i>--}}
        {{--                    Monitor--}}
        {{--                </a>--}}
        {{--            </li>--}}
    @else
        <li class="nav-item ml-3">
            <a class="nav-link fs-11 ml-5" href="{{$globusTransfer->globus_url}}" target="_blank">
                <i class="fa-fw fas fa-arrow-alt-circle-right mr-2"></i>
                Use
            </a>
        </li>

        <li class="nav-item ml-3">
            <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.globus2.close')}}"
               href="{{route('projects.globus2.close', [$project])}}">
                <i class="fa-fw fas fa-check mr-2"></i>
                Done
            </a>
        </li>

        {{--            <li class="nav-item ml-3">--}}
        {{--                <a class="nav-link fs-11 ml-5 {{setActiveNavByName('projects.globus.monitor')}}"--}}
        {{--                   href="{{route('projects.globus.monitor', [$project])}}">--}}
        {{--                    <i class="fa-fw fas fa-eye mr-2"></i>--}}
        {{--                    Monitor--}}
        {{--                </a>--}}
        {{--            </li>--}}
    @endif
</div>
