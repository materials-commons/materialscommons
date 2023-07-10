<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($showRouteName)}}"
           href="{{route($showRouteName, [$community])}}">
            Overview
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($datasetsRouteName)}}"
           href="{{route($datasetsRouteName, [$community])}}">
            Datasets
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName($filesRouteName)}}"
           href="{{route($filesRouteName, [$community])}}">
            Standards
        </a>
    </li>

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link no-underline {{setActiveNavByName($linksRouteName)}}"--}}
    {{--           href="{{route($linksRouteName, [$community])}}">--}}
    {{--            Contributors--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>
