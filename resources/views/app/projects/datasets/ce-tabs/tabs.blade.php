<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($defaultRouteName)}}" href="{{$defaultRoute}}">
            Files
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($workflowsRouteName)}}" href="{{$workflowsRoute}}">
            Workflows
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($samplesRouteName)}}" href="{{$samplesRoute}}">
            Samples
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName($processesRouteName)}}" href="{{$processesRoute}}">--}}
    {{--            Processes--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>