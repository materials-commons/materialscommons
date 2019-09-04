<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10 sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-3">
            {{--                    <li class="nav-item">--}}
            {{--                        <a class="nav-link fs-11 {{setActiveNav('dashboard')}}" href="{{route('dashboard.index')}}">--}}
            {{--                            <span data-feather="home"></span>--}}
            {{--                            <i class="fa-fw fas fa-binoculars mr-2"></i>--}}
            {{--                            Dashboard--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item">--}}
            {{--                        <a class="nav-link fs-11 {{setActiveNav('teams')}}" href="{{route('teams.index')}}">--}}
            {{--                            <i class="fa-fw fas fa-address-book mr-2 {{setActiveNav('teams')}}"></i>--}}
            {{--                            Teams--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('projects')}}" href="{{route('projects.index')}}">
                    <i class="fa-fw fas fa-project-diagram mr-2 "></i>
                    Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('tasks')}}" href="{{route('tasks.index')}}">
                    <i class="fa-fw fas fa-tasks mr-2"></i>
                    Tasks
                </a>
            </li>
            {{--                    <li class="nav-item">--}}
            {{--                        <a class="nav-link fs-11 {{setActiveNav('files')}}" href="{{route('files.index')}}">--}}
            {{--                            <i class="fa-fw fas fa-folder mr-2 {{setActiveNav('files')}}"></i>--}}
            {{--                            Files--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item">--}}
            {{--                        <a class="nav-link fs-11 {{setActiveNav('users')}}" href="{{route('users.index')}}">--}}
            {{--                            <i class="fa-fw fas fa-users-cog mr-2 {{setActiveNav('users')}}"></i>--}}
            {{--                            Users--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('settings')}}" href="{{route('settings.index')}}">
                    <i class="fa-fw fas fa-cogs mr-2 {{setActiveNav('settings')}}"></i>
                    Settings
                </a>
            </li>

            <li class="nav-item">
                <br>
                <a class="nav-link fs-11" href="{{route('public.index')}}">
                    <i class="fa-fw fas fa-globe mr-2"></i>
                    Public Data
                </a>
            </li>
        </ul>

        {{--        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">--}}
        {{--            <span class="fs-11">Saved Reports</span>--}}
        {{--            <a class="d-flex align-items-center text-muted" href="#">--}}
        {{--            </a>--}}
        {{--        </h6>--}}

        {{--        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">--}}
        {{--            <span class="fs-11">Saved Searches</span>--}}
        {{--        </h6>--}}

        {{--        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">--}}
        {{--            <span class="fs-11">Quick Access</span>--}}
        {{--        </h6>--}}
    </div>
</nav>
