<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($defaultRouteName)}}" href="{{$defaultRoute}}">
            Details
        </a>
    </li>

    @isset($filesRouteName)
        <li class="nav-item">
            <a class="nav-link no-underline {{setActiveNavByName($filesRouteName)}}" href="{{$filesRoute}}">
                Files
            </a>
        </li>
    @endisset

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($samplesRouteName)}}" href="{{$samplesRoute}}">
            Samples
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($workflowsRouteName)}}" href="{{$workflowsRoute}}">
            Workflows
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName($processesRouteName)}}" href="{{$processesRoute}}">--}}
    {{--            Processes--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>
