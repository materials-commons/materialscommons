<div class="sidebar-group compact-nav">
    <div class="sidebar-group-header">
        <i class="fas fa-compass fa-sm me-2 mb-1"></i>Navigation
    </div>
    <ul class="nav flex-column mt-3">
        <li class="nav-item" id="dashboard-sidebar">
            <a class="nav-link fs-11 {{setActiveNav('dashboard')}}" href="{{route('dashboard')}}">
                <i class="fa-fw fas fa-tachometer-alt me-2 mb-1"></i>
                Dashboard
            </a>
        </li>

        @if(auth()->user()->is_admin)
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('admin')}}" href="{{route('admin.dashboard')}}">
                    <i class="fa-fw fas fa-user-shield me-2 mb-1"></i> Admin
                </a>
            </li>
        @endif

        @if(isInBeta('site-statistics'))
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('site')}}" href="{{route('site.statistics')}}">
                    <i class="fa-fw fas fa-chart-line me-2 mb-1"></i> Site Statistics
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link fs-11" href="{{route('public.index')}}">
                <i class="fa-fw fas fa-globe me-2 mb-1"></i>
                Public Data
            </a>
        </li>

        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link fs-11 {{setActiveNav('teams')}}" href="{{route('teams.index')}}">--}}
        {{--                    <i class="fa-fw fas fa-users me-2"></i>--}}
        {{--                    My Teams--}}
        {{--                </a>--}}
        {{--            </li>--}}

        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link fs-11 {{setActiveNav('tasks')}}" href="{{route('tasks.index')}}">--}}
        {{--                    <i class="fa-fw fas fa-tasks me-2"></i>--}}
        {{--                    Tasks--}}
        {{--                </a>--}}
        {{--            </li>--}}

        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNav('accounts')}}" href="{{route('accounts.show')}}">
                <i class="fa-fw fas fa-user me-2 mb-1"></i>
                Account
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">
                <i class="fa-fw fas fa-city me-2 mb-1"></i>
                My Communities
            </a>
        </li>

        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link fs-11 {{setActiveNav('settings')}}" href="{{route('settings.index')}}">--}}
        {{--                    <i class="fa-fw fas fa-cogs me-2 {{setActiveNav('settings')}}"></i>--}}
        {{--                    Settings--}}
        {{--                </a>--}}
        {{--            </li>--}}

    </ul>
</div>

