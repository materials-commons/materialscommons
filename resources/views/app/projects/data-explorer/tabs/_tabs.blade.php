<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.data-explorer.samples')}}"
           href="{{route('projects.data-explorer.samples', [$project])}}">
            Samples Table
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.data-explorer.sample-details')}}"
           href="{{route('projects.data-explorer.sample-details', [$project])}}">
            Sample Details
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.data-explorer.sample-files')}}"
           href="{{route('projects.data-explorer.sample-files', [$project])}}">
            Files
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.data-explorer.sample-attributes')}}"
           href="{{route('projects.data-explorer.sample-attributes', [$project])}}">
            Sample Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.data-explorer.process-attributes')}}"
           href="{{route('projects.data-explorer.process-attributes', [$project])}}">
            Process Attributes
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('projects.data-explorer.data-source')}}"
           href="{{route('projects.data-explorer.data-source', [$project])}}">
            Data Source
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link no-underline {{setActiveNavByName('projects.documents.show')}}"--}}
    {{--           href="{{route('projects.documents.show', [$project])}}">--}}
    {{--            Units--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>