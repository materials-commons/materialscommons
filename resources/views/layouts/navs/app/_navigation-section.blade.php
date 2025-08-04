<div class="sidebar-group compact-nav">
    <div class="sidebar-group-header">
        <i class="fas fa-compass fa-sm mr-2 mb-1"></i>Navigation
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNav('dashboard')}}"
               data-toggle="tooltip"
               title="Go to the dashboard listing all your projects and published datasets."
               href="{{route('dashboard')}}">
                <i class="fa-fw fas fa-tachometer-alt mr-2 mb-1"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">
                <i class="fa-fw fas fa-city mr-2 mb-1"></i>My Communities
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-11 {{setActiveNavByName('accounts.show')}}" href="{{route('accounts.show')}}">
                <i class="fa-fw fas fa-user mr-2 mb-1"></i>Account
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-11" href="{{route('public.index')}}">
                <i class="fa-fw fas fa-globe mr-2 mb-1"></i>Public Data
            </a>
        </li>
    </ul>
</div>

