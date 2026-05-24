<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10x bg-whitex sidebar" id="project-sidebar"
     style="background-color: #f5f5f5">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-1 mb-3">

            {{-- Top-level nav --}}
            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-compass me-2"></i>Navigation</span>
            </li>

            <li class="nav-item" id="dashboard-sidebar">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('dashboard')}}" href="{{route('dashboard')}}">
                    <i class="fa-fw fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('dashboard.browse-tree')}}"
                   data-toggle="tooltip" title="Access your project files."
                   href="{{route('dashboard.browse-tree.show')}}">
                    <i class="fa-fw fas fa-sitemap me-2"></i>
                    Browse
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

            {{-- Explore --}}
            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-globe me-2"></i>Published Datasets</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('public.datasets')}}"
                   href="{{route('public.index')}}">
                    <i class="fa-fw fas fa-database me-2"></i>
                    Browse Datasets
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3" href="{{route('public.tags.index')}}">
                    <i class="fa-fw fas fa-tags me-2"></i>
                    Browse by Tag
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3" href="{{route('public.authors.index')}}">
                    <i class="fa-fw fas fa-users me-2"></i>
                    Browse Authors
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3" href="{{route('public.communities.index')}}">
                    <i class="fa-fw fas fa-layer-group me-2"></i>
                    Browse Communities
                </a>
            </li>

            {{-- My Work --}}
            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-briefcase me-2"></i>My Work</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('dashboard')}}" href="{{route('dashboard')}}">
                    <i class="fa-fw fas fa-folder-open me-2"></i>
                    My Projects
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('dashboard.published-datasets')}}"
                   href="{{route('dashboard.published-datasets.show')}}">
                    <i class="fa-fw fas fa-book me-2"></i>
                    My Datasets
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('communities')}}"
                   href="{{route('communities.index')}}">
                    <i class="fa-fw fas fa-city me-2"></i>
                    My Communities
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNav('accounts')}}"
                   href="{{route('accounts.show')}}">
                    <i class="fa-fw fas fa-user me-2"></i>
                    Account
                </a>
            </li>

            {{-- Resources --}}
            @include('layouts.navs._nav-resources')

        </ul>
    </div>
</nav>
