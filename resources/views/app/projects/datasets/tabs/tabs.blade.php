<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datasets.show.overview')}}"
           href="{{route('projects.datasets.show.overview', [$project, $dataset])}}">
            Overview
        </a>
    </li>

    <li class="nav-item">
        @if(Request::routeIs('projects.datasets.show.files'))
            <a class="nav-link active no-underline"
               href="{{route('projects.datasets.show.files', [$project, $dataset])}}">
                Files
            </a>
        @elseif(Request::routeIs('projects.datasets.show.next'))
            <a class="nav-link active no-underline"
               href="{{route('projects.datasets.show.files', [$project, $dataset])}}">
                Files
            </a>
        @else
            <a class="nav-link no-underline" href="{{route('projects.datasets.show.files', [$project, $dataset])}}">
                Files
            </a>
        @endif
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datasets.show.file_includes_excludes')}}"
           href="{{route('projects.datasets.show.file_includes_excludes', [$project, $dataset])}}">
            Includes/Excludes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datasets.show.entities')}}"
           href="{{route('projects.datasets.show.entities', [$project, $dataset])}}">
            Samples
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName('projects.datasets.show.data-dictionary')}}"--}}
    {{--           href="{{route('projects.datasets.show.data-dictionary', [$project, $dataset])}}">--}}
    {{--            Data Dictionary--}}
    {{--        </a>--}}
    {{--    </li>--}}

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datasets.show.workflows')}}"
           href="{{route('projects.datasets.show.workflows', [$project, $dataset])}}">
            Workflows
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName('projects.datasets.show.actvities')}}"--}}
    {{--           href="{{route('projects.datasets.show.activities', [$project, $dataset])}}">--}}
    {{--            Processes--}}
    {{--        </a>--}}
    {{--    </li>--}}

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName('projects.datasets.show.experiments')}}"--}}
    {{--           href="{{route('projects.datasets.show.experiments', [$project, $dataset])}}">--}}
    {{--            Experiments--}}
    {{--        </a>--}}
    {{--    </li>--}}

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.datasets.show.communities')}}"
           href="{{route('projects.datasets.show.communities', [$project, $dataset])}}">
            Communities
        </a>
    </li>
</ul>
