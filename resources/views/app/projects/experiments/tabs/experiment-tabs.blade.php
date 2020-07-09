<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.show')}}"
           href="{{route('projects.experiments.show', [$project, $experiment])}}">
            Samples
        </a>
    </li>
    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName('projects.experiments.data-dictionary')}}"--}}
    {{--           href="{{route('projects.experiments.data-dictionary', [$project, $experiment])}}">--}}
    {{--            Data Dictionary--}}
    {{--        </a>--}}
    {{--    </li>--}}
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName('projects.experiments.workflow')}}"
           href="{{route('projects.experiments.workflow', [$project, $experiment])}}">
            Workflow
        </a>
    </li>
    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName('projects.experiments.activities-tab')}}"--}}
    {{--           href="{{route('projects.experiments.activities-tab', [$project, $experiment])}}">--}}
    {{--            Processes--}}
    {{--        </a>--}}
    {{--    </li>--}}
    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link" href="#">Files</a>--}}
    {{--    </li>--}}
</ul>
