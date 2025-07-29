<nav class="col-md-2 col-sm-2 d-none d-md-block bg-sidebar sidebar" id="project-sidebar">
    <div class="sidebar-sticky">
        <div class="sidebar-group">
            <div class="sidebar-group-header">
                <i class="fas fa-compass fa-sm mr-2"></i>Navigation
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link fs-11 {{setActiveNav('dashboard')}}"
                       data-toggle="tooltip"
                       title="Go to the dashboard listing all your projects and published datasets."
                       href="{{route('dashboard')}}">
                        <i class="fa-fw fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-11 {{setActiveNav('communities')}}" href="{{route('communities.index')}}">
                        <i class="fa-fw fas fa-city mr-2"></i>My Communities
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-11 {{setActiveNavByName('accounts.show')}}" href="{{route('accounts.show')}}">
                        <i class="fa-fw fas fa-user mr-2"></i>Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-11" href="{{route('public.index')}}">
                        <i class="fa-fw fas fa-globe mr-2"></i>Public Data
                    </a>
                </li>
            </ul>
        </div>


        {{-- Project Context --}}
        <div class="sidebar-group">
            <div class="sidebar-group-header">
                <i class="fas fa-project-diagram fa-sm mr-2"></i>Current Project
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link fs-11 project-name {{setActiveNavByName('projects.show')}}"
                       data-toggle="tooltip"
                       title="View details about your project."
                       href="{{route('projects.show', ['project' => $project->id])}}">
                        <i class="fa-fw fas fa-vector-square mr-2" style="color:#319795"></i>
                        {{$project->name}}
                    </a>
                </li>
            </ul>
        </div>

        {{-- Project Sections --}}
        <div class="project-sections">
            @include('layouts.navs.app._data-section')
            @include('layouts.navs.app._organization-section')
            @include('layouts.navs.app._actions-section')
        </div>

        {{-- Global Footer Navigation --}}
{{--        <div class="sidebar-group footer-nav">--}}
{{--            <ul class="nav flex-column">--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link fs-11 {{setActiveNav('communities')}}"--}}
{{--                       data-toggle="tooltip" title="Create and manage your research communities."--}}
{{--                       href="{{route('communities.index')}}">--}}
{{--                        <i class="fa-fw fas fa-city mr-2"></i>My Communities--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link fs-11 {{setActiveNavByName('accounts.show')}}"--}}
{{--                       data-toggle="tooltip" title="Access your account details."--}}
{{--                       href="{{route('accounts.show')}}">--}}
{{--                        <i class="fa-fw fas fa-user mr-2"></i>Account--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link fs-11"--}}
{{--                       data-toggle="tooltip" title="Access the public published datasets site."--}}
{{--                       href="{{route('public.index')}}">--}}
{{--                        <i class="fa-fw fas fa-globe mr-2"></i>Public Data--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div>--}}
    </div>
</nav>
