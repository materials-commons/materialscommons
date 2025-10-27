<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10x bg-whitex sidebar" id="project-sidebar"
     style="background-color: #f5f5f5">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-1 mb-3">
            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-compass me-2"></i>Navigation</span>
            </li>

            <li class="nav-item" id="dashboard-sidebar">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('dashboard')}}" href="{{route('dashboard')}}">
                    <i class="fa-fw fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            @if(auth()->user()->is_admin)
                <li class="nav-item">
                    <a class="nav-link fs-11 ms-3 {{setActiveNav('admin')}}" href="{{route('admin.dashboard')}}">
                        <i class="fa-fw fas fa-user-shield me-2"></i>
                        Admin
                    </a>
                </li>
            @endif

            @if(isInBeta('site-statistics'))
                <li class="nav-item">
                    <a class="nav-link fs-11 ms-3 {{setActiveNav('site')}}" href="{{route('site.statistics')}}">
                        <i class="fa-fw fas fa-chart-line me-2"></i>
                        Site Statistics
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3" href="{{route('public.index')}}">
                    <i class="fa-fw fas fa-globe me-2"></i>
                    Public Data
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('accounts')}}" href="{{route('accounts.show')}}">
                    <i class="fa-fw fas fa-user me-2"></i>
                    Account
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">
                    <i class="fa-fw fas fa-city me-2"></i>
                    My Communities
                </a>
            </li>

            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-book me-2"></i>Documentation</span>
            </li>

            @include('layouts.navs._app-documentation')
        </ul>
    </div>
</nav>
