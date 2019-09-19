<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($showRouteName)}}" href="{{$showRoute}}">
            File
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($entitiesRouteName)}}" href="{{$entitiesRoute}}">
            Entities
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($activitiesRouteName)}}"
           href="{{$activitiesRoute}}">
            Activities
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link {{setActiveNavByName($attributesRouteName)}}"--}}
    {{--           href="{{$attributesRoute}}">--}}
    {{--            Attributes--}}
    {{--        </a>--}}
    {{--    </li>--}}

    <li class="nav-item">
        <a class="nav-link {{setActiveNavByName($experimentsRouteName)}}"
           href="{{$experimentsRoute}}">
            Experiments
        </a>
    </li>
</ul>
