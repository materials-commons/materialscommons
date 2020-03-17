<nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10 sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column mt-3">
            @auth
                {{--                <li class="nav-item">--}}
                {{--                    <a class="nav-link fs-11 {{setActiveNav('dashboard')}}" href="{{route('dashboard')}}">--}}
                {{--                        <i class="fa-fw fas fa-tachometer-alt mr-2"></i>--}}
                {{--                        Dashboard--}}
                {{--                    </a>--}}
                {{--                </li>--}}

                <li class="nav-item">
                    <a class="nav-link fs-11 {{setActiveNav('projects')}}" href="{{route('projects.index')}}">
                        <i class="fa-fw fas fa-layer-group mr-2 "></i>
                        Projects
                    </a>
                </li>
            @endauth

            <li class="nav-item">
                <a class="nav-link fs-11 {{setActiveNavByName('public.index')}}" href="{{route('public.index')}}">
                    <i class="fa-fw fas fa-globe mr-2"></i>
                    Public Data
                </a>
            </li>

            <li class="nav-item">
                @auth
                    <a class="nav-link fs-11 ml-3 {{setActiveNavByName('public.publish')}}"
                       href="{{route('public.publish.wizard.choose_create_or_select_project')}}">
                        <i class="fa-fw fas fa-upload mr-2"></i>
                        Publish
                    </a>
                @else
                    <a class="nav-link fs-11 ml-3" href="{{route('login-for-upload')}}">
                        <i class="fa-fw fas fa-upload mr-2"></i>
                        Publish
                    </a>
                @endauth
            </li>


            {{--            <li class="nav-item">--}}
            {{--                <a class="nav-link fs-11 ml-3 {{setActiveNavByName('public.new')}}"--}}
            {{--                   href="{{route('public.new.index')}}">--}}
            {{--                    <i class="fa-fw fas fa-bullhorn mr-2"></i>--}}
            {{--                    What's New--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--                    <li class="nav-item">--}}
            {{--                        <a class="nav-link fs-11 ml-3 {{setActiveNavByName('public.projects')}}" href="{{route('public.projects.index')}}">--}}
            {{--                            <span data-feather="home"></span>--}}
            {{--                            <i class="fa-fw fas fa-project-diagram mr-2"></i>--}}
            {{--                            Projects--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            <li class="nav-item">
                <a class="nav-link fs-11 ml-3 {{setActiveNavByName('public.communities')}}"
                   href="{{route('public.communities.index')}}">
                    <i class="fa-fw fas fa-users mr-2"></i>
                    Communities
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-3 {{setActiveNavByName('public.datasets')}}"
                   href="{{route('public.datasets.index')}}">
                    <i class="fa-fw fas fa-book mr-2"></i>
                    Datasets
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-11 ml-3 {{setActiveNavByName('public.authors')}}"
                   href="{{route('public.authors.index')}}">
                    <i class="fa-fw fas fa-user-friends mr-2"></i>
                    Authors
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fs-11 ml-3 {{setActiveNavByName('public.tags')}}"
                   href="{{route('public.tags.index')}}">
                    <i class="fa-fw fas fa-tags mr-2"></i>
                    Tags
                </a>
            </li>

            @auth
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
            @endauth

        </ul>
    </div>
</nav>
