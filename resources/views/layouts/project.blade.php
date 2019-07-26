<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="/favicon.ico" type="image/x-icon"/>

    <title>
        @hasSection('pageTitle')
            @yield('pageTitle')
        @else
            Illuminate
        @endif
    </title>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
          integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- Custom styles for this template -->
</head>

<body>
<nav class="navbar navbar-dark fixed-top bg-nav p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('dashboard.index')}}">MaterialsCommons</a>
{{--    <div class="d-flex col-sm-8 justify-content-end">--}}
{{--        <a class="nav-link action col-sm-3 col-md-2 mr-0" href="{{route('dashboard.index')}}">Projects</a>--}}
{{--        <a class="nav-link action col-sm-3 col-md-2 mr-0" href="{{route('dashboard.index')}}">Stuff</a>--}}
{{--    </div>--}}
    <div class="d-flex justify-content-endx">
        <input class="form-control w-75 form-rounded" type="text" placeholder="Search" aria-label="Search">

        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <form method="post" action="{{route('logout')}}" id="signout">
                    @csrf
                    <a class="nav-link td-none" href="#" onclick="document.getElementById('signout').submit()">Sign out</a>
                </form>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 col-sm-2 d-none d-md-block bg-grey-10 sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link fs-11 {{setActiveNavByName('projects.show')}}" href="{{route('projects.show', ['project' => $project->id])}}">
                            <span data-feather="home"></span>
                            <i class="fa-fw fas fa-project-diagram mr-2"></i>
                            {{$project->name}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-11 ml-2 {{setActiveNavByName('projects.experiments')}}" href="{{route('projects.experiments.index', ['project' => $project->id])}}">
                            <i class="fa-fw fas fa-flask mr-2"></i>
                            Experiments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-11 ml-2 {{setActiveNavByName('projects.samples')}}" href="{{route('projects.samples.index', ['project' => $project->id])}}">
                            <i class="fa-fw fas fa-cubes mr-2 "></i>
                            Samples
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-11 ml-2 {{setActiveNavByName('projects.processes')}}" href="{{route('projects.processes.index', ['project' => $project->id])}}">
                            <i class="fa-fw fas fa-code-branch mr-2"></i>
                            Processes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-11 ml-2 {{setActiveNavByName('projects.files')}}" href="{{route('projects.files.index', ['project' => $project->id])}}">
                            <i class="fa-fw fas fa-folder mr-2"></i>
                            Files
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-11 ml-2 {{setActiveNavByName('projects.users')}}" href="{{route('projects.users.index', ['project' => $project->id])}}">
                            <i class="fa-fw fas fa-users-cog mr-2"></i>
                            Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-11 ml-2 {{setActiveNavByName('projects.settings')}}" href="{{route('projects.settings.index', ['project' => $project->id])}}">
                            <i class="fa-fw fas fa-cogs mr-2"></i>
                            Settings
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fs-11 ml-2" href="{{route('public.index')}}">
                            <i class="fa-fw fas fa-globe mr-2"></i>
                            Public Data
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span class="fs-11">Saved Reports</span>
                    <a class="d-flex align-items-center text-muted" href="#">
                    </a>
                </h6>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span class="fs-11">Saved Searches</span>
                </h6>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span class="fs-11">Quick Access</span>
                </h6>
                {{--<ul class="nav flex-column mb-2">--}}
                {{--<li class="nav-item">--}}
                {{--<a class="nav-link" href="#">--}}
                {{--<span data-feather="file-text"></span>--}}
                {{--Current month--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li class="nav-item">--}}
                {{--<a class="nav-link" href="#">--}}
                {{--<span data-feather="file-text"></span>--}}
                {{--Last quarter--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li class="nav-item">--}}
                {{--<a class="nav-link" href="#">--}}
                {{--<span data-feather="file-text"></span>--}}
                {{--Social engagement--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li class="nav-item">--}}
                {{--<a class="nav-link" href="#">--}}
                {{--<span data-feather="file-text"></span>--}}
                {{--Year-end sale--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--</ul>--}}
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="mt-3">
                @yield('content')
            </div>
        </main>
    </div>
</div>

</body>
</html>
