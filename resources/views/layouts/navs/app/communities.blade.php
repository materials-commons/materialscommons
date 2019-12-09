<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10 sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('projects')}}" href="{{route('projects.index')}}">
                    <i class="fa-fw fas fa-project-diagram mr-2 "></i>
                    Projects
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('accounts')}}" href="{{route('accounts.show')}}">
                    <i class="fa-fw fas fa-user mr-2"></i>
                    Account
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">
                    <i class="fa-fw fas fa-users mr-2"></i>
                    Communities
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-3">
                    <i class="fa-fw fas fa-users-cog mr-2"></i>
                    My Communities
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-3">
                    <i class="fa-fw fas fa-user-friends mr-2"></i>
                    Joined Communities
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11" href="{{route('public.index')}}">
                    <i class="fa-fw fas fa-globe mr-2"></i>
                    Public Data
                </a>
            </li>
        </ul>

    </div>
</nav>
