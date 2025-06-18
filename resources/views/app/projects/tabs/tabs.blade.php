<ul class="nav nav-tabs">
    <li class="nav-item" id="project-home-tab">
        <a wire:navigate class="nav-link no-underline {{setActiveNavByName('projects.show')}}"
           href="{{route('projects.show', [$project])}}">
            Home
        </a>
    </li>

    <li class="nav-item" id="project-overview-tab">
        <a wire:navigate class="nav-link no-underline {{setActiveNavByName('projects.overview')}}"
           href="{{route('projects.overview', [$project])}}">
            Overview
        </a>
    </li>

    <li class="nav-item" id="project-sample-attributes-tab">
        <a wire:navigate class="nav-link no-underline {{setActiveNavByName('projects.data-dictionary.entities')}}"
           href="{{route('projects.data-dictionary.entities', [$project])}}">
            Sample Attributes ({{$entityAttributesCount}})
        </a>
    </li>

    <li class="nav-item" id="project-process-attributes-tab">
        <a wire:navigate class="nav-link no-underline {{setActiveNavByName('projects.data-dictionary.activities')}}"
           href="{{route('projects.data-dictionary.activities', [$project])}}">
            Process Attributes ({{$activityAttributesCount}})
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link no-underline {{setActiveNavByName('projects.documents.show')}}"--}}
    {{--           href="{{route('projects.documents.show', [$project])}}">--}}
    {{--            Units--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>
