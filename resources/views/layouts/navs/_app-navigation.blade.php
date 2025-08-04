<div class="sidebar-group compact-nav">
    <div class="sidebar-group-header">
        <i class="fas fa-compass fa-sm mr-2"></i>Navigation
    </div>
    <ul class="nav flex-column mt-3">
        <li class="nav-item" id="dashboard-sidebar">
            <a class="nav-link fs-11 {{setActiveNav('dashboard')}}" href="{{route('dashboard')}}">
                <i class="fa-fw fas fa-tachometer-alt mr-2"></i>
                Dashboard
            </a>
        </li>

        @if(auth()->user()->is_admin)
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('admin')}}" href="{{route('admin.dashboard')}}">
                    <i class="fa-fw fas fa-user-shield mr-2"></i> Admin
                </a>
            </li>
        @endif

        @if(isInBeta('site-statistics'))
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('site')}}" href="{{route('site.statistics')}}">
                    <i class="fa-fw fas fa-chart-line mr-2"></i> Site Statistics
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link fs-11" href="{{route('public.index')}}">
                <i class="fa-fw fas fa-globe mr-2"></i>
                Public Data
            </a>
        </li>

        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link fs-11 {{setActiveNav('teams')}}" href="{{route('teams.index')}}">--}}
        {{--                    <i class="fa-fw fas fa-users mr-2"></i>--}}
        {{--                    My Teams--}}
        {{--                </a>--}}
        {{--            </li>--}}

        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link fs-11 {{setActiveNav('tasks')}}" href="{{route('tasks.index')}}">--}}
        {{--                    <i class="fa-fw fas fa-tasks mr-2"></i>--}}
        {{--                    Tasks--}}
        {{--                </a>--}}
        {{--            </li>--}}

        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNav('accounts')}}" href="{{route('accounts.show')}}">
                <i class="fa-fw fas fa-user mr-2"></i>
                Account
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">
                <i class="fa-fw fas fa-city mr-2"></i>
                My Communities
            </a>
        </li>

        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link fs-11 {{setActiveNav('settings')}}" href="{{route('settings.index')}}">--}}
        {{--                    <i class="fa-fw fas fa-cogs mr-2 {{setActiveNav('settings')}}"></i>--}}
        {{--                    Settings--}}
        {{--                </a>--}}
        {{--            </li>--}}

    </ul>
</div>

