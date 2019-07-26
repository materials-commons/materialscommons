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

    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{--    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">--}}

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>

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
                        <a class="nav-link fs-11 {{setActiveNav('tasks')}}" href="{{route('tasks.index')}}">
                            <i class="fa-fw fas fa-bullhorn mr-2"></i>
                            What's New
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fs-11 {{setActiveNav('dashboard')}}" href="{{route('dashboard.index')}}">
                            <span data-feather="home"></span>
                            <i class="fa-fw fas fa-project-diagram mr-2"></i>
                            Projects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-11 {{setActiveNav('teams')}}" href="{{route('teams.index')}}">
                            <i class="fa-fw fas fa-book mr-2 {{setActiveNav('teams')}}"></i>
                            Datasets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-11 {{setActiveNav('projects')}}" href="{{route('projects.index')}}">
                            <i class="fa-fw fas fa-user-friends mr-2 "></i>
                            Authors
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fs-11 {{setActiveNav('files')}}" href="{{route('files.index')}}">
                            <i class="fa-fw fas fa-tags mr-2 {{setActiveNav('files')}}"></i>
                            Tags
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="mt-3">
                @yield('content')
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
