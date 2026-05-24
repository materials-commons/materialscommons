{{--<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10 sidebar">--}}
<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10x bg-whitex sidebar" style="background-color: #f5f5f5">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-1 mb-3">

            {{-- Back to dashboard (auth) or home (guest) --}}
            @auth
                <li class="nav-item mt-2">
                    <a class="nav-link fs-11 ms-2 text-muted" href="{{route('dashboard')}}">
                        <i class="fa-fw fas fa-arrow-left me-2"></i>
                        Dashboard
                    </a>
                </li>
            @else
                <li class="nav-item mt-2">
                    <a class="nav-link fs-11 ms-2 text-muted" href="{{route('welcome')}}">
                        <i class="fa-fw fas fa-arrow-left me-2"></i>
                        Home
                    </a>
                </li>
            @endauth

            {{-- Publish CTA --}}
            <li class="nav-item mt-2">
                @auth
                    <a class="nav-link fs-11 ms-2 {{setActiveNavByName('public.publish')}}"
                       href="{{route('public.publish.wizard.choose_create_or_select_project')}}">
                        <i class="fa-fw fas fa-file-export me-2"></i>
                        Publish Your Data
                    </a>
                @else
                    <a class="nav-link fs-11 ms-2" href="{{route('login-for-upload')}}">
                        <i class="fa-fw fas fa-file-export me-2"></i>
                        Publish Your Data
                    </a>
                @endauth
            </li>

            {{-- Published Data --}}
            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-globe me-2"></i>Published Data</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('public.datasets')}}"
                   href="{{route('public.datasets.index')}}">
                    <i class="fa-fw fas fa-database me-2"></i>
                    Browse Datasets
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('public.tags')}}"
                   href="{{route('public.tags.index')}}">
                    <i class="fa-fw fas fa-tags me-2"></i>
                    Browse by Tag
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('public.authors')}}"
                   href="{{route('public.authors.index')}}">
                    <i class="fa-fw fas fa-users me-2"></i>
                    Browse Authors
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3" href="{{route('public.communities.index')}}">
                    <i class="fa-fw fas fa-city me-2"></i>
                    Browse Communities
                </a>
            </li>



            {{-- Special Collections --}}
            <li class="nav-item mt-3">
                <span class="ms-3 fs-11"><i class="fas fa-layer-group me-2"></i>Special Collections</span>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('public.openvisus')}}"
                   href="{{route('public.openvisus.index', ['tag' => 'OpenVisus'])}}">
                    <i class="fa-fw fas fa-cube me-2"></i>
                    OpenVisus Datasets
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ms-3 {{setActiveNavByName('public.uhcsdb')}}"
                   href="/uhcsdb">
                    <i class="fa-fw fas fa-atlas me-2"></i>
                    UHCSDB
                    <i class="fa-fw fas fa-question-circle ms-2"
                       data-bs-toggle="tooltip"
                       title="A dataset explorer of hierarchical structures in Ultrahigh carbon steel"></i>
                </a>
            </li>

            {{-- My Work (auth only) --}}
            @auth
                <li class="nav-item mt-3">
                    <span class="ms-3 fs-11"><i class="fas fa-briefcase me-2"></i>My Work</span>
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
            @endauth

            {{-- Resources --}}
            @include('layouts.navs._nav-resources')

        </ul>
    </div>
</nav>
