<ul class="nav nav-pills">
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

    @if(Request::routeIs('communities.*'))
        <li class="nav-item">
            <a class="nav-link no-underline {{setActiveNavByName('communities.waiting-approval.index')}}"
               href="{{route('communities.waiting-approval.index', [$community])}}">
                Awaiting Approval ({{$community->datasetsWaitingForApproval->count()}})
            </a>
        </li>
    @endif

    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link no-underline {{setActiveNavByName($linksRouteName)}}"--}}
    {{--           href="{{route($linksRouteName, [$community])}}">--}}
    {{--            Contributors--}}
    {{--        </a>--}}
    {{--    </li>--}}
</ul>
