<div class="sidebar-group compact-nav">
{{--    <div class="sidebar-group-header">--}}
{{--        <i class="fas fa-compass fa-sm me-2 mb-1"></i>Navigation--}}
{{--    </div>--}}
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNav('dashboard')}}"
               data-bs-toggle="tooltip"
               title="Go to the dashboard listing all your projects and published datasets."
               href="{{route('dashboard')}}" style="color:#2B6BB1; font-weight: bold;">
                <i class="fa-fw fas fa-tachometer-alt me-2 mb-1"></i>Dashboard
            </a>
        </li>
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link fs-11 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">--}}
{{--                <i class="fa-fw fas fa-city me-2 mb-1"></i>My Communities--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link fs-11 {{setActiveNavByName('accounts.show')}}" href="{{route('accounts.show')}}">--}}
{{--                <i class="fa-fw fas fa-user me-2 mb-1"></i>Account--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link fs-11" href="{{route('public.index')}}">--}}
{{--                <i class="fa-fw fas fa-globe me-2 mb-1"></i>Public Data--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link fs-11" data-bs-toggle="modal" href="#help-dialog">--}}
{{--                <i class="fa-fw fas fa-question-circle help-icon me-2 mb-1"></i>Get Help--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>
</div>

