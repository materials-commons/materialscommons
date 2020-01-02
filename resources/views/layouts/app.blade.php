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
            MaterialsCommons
        @endif
    </title>

    @routes

    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{asset('css/datatables.min.css')}}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('js/datatables.min.js')}}"></script>

    <link href="{{asset('css/fa/css/all.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-nav fixed-top p-0">
    @if(Request::routeIs('public.*'))
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('public.index')}}">MaterialsCommons 2</a>
    @else
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('projects.index')}}">MaterialsCommons 2</a>
    @endif
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbar5">
        <ul class="navbar-nav">
            {{--            Kept here for formatting purposes--}}
            <li class="nav-item">
                <a class="nav-link outline-none td-none navbar-brand help-color"
                   href="{{helpUrl()}}" target="_blank">
                    Help
                </a>
            </li>
        </ul>
        <form class="mx-2 my-auto d-inline w-75">
            {{--            <div class="input-group">--}}
            <input type="text" class="form-control form-rounded border border-right-0" placeholder="Search...">
            {{--                <span class="input-group-append">--}}
            {{--                    <button class="btn btn-outline-secondary border border-left-0" type="button">--}}
            {{--                        <i class="fa fa-search"></i>--}}
            {{--                    </button>--}}
            {{--                </span>--}}
            {{--            </div>--}}
        </form>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    {{auth()->user()->name}}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

@if(false)
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-nav p-0 shadow">
        @if(Request::routeIs('public.*'))
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('public.index')}}">MaterialsCommons 2</a>
        @else
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('projects.index')}}">MaterialsCommons 2</a>
        @endif
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                {{--            Kept here for formatting purposes--}}
                <li class="nav-item">
                    <a class="nav-link outline-none td-none navbar-brand help-color"
                       href="{{helpUrl()}}" target="_blank">
                        Help
                    </a>
                </li>
            </ul>

            @auth
                @isset($project)
                    <form method="post" action="{{route('projects.search', [$project])}}"
                          class="form-inline my-2 my-lg-0 d-inline w-50">
                        @csrf
                        <input class="form-control form-rounded" type="search"
                               placeholder="Search project..."
                               name="search" aria-label="Search">
                    </form>
                @endisset
                <ul class="navbar-nav pr-6">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle td-none outline-none" href="#" id="navbarDropdown"
                           role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{auth()->user()->name}}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <form method="post" action="{{route('logout')}}" id="signout">
                                @csrf
                                <a class="dropdown-item" href="#" onclick="document.getElementById('signout').submit()">
                                    Sign out</a>
                            </form>
                        </div>
                    </li>

                    <li class="nav-item">
                        <span class="navbar-spacing"></span>
                    </li>
                </ul>
            @else
                <form method="post" action="" class="form-inline my-2 my-lg-0">
                    @csrf
                    <input class="form-control mr-sm-2 w-auto form-rounded" type="search"
                           placeholder="Search datasets..."
                           name="search" aria-label="Search">
                </form>
                <ul class="navbar-nav pr-6">
                    <li class="nav-item text-nowrap">
                        <a class="nav-link td-none" href="{{route('login')}}">Sign in/Register</a>
                    </li>
                    <li class="nav-item">
                        <span class="navbar-spacing"></span>
                    </li>
                </ul>
            @endauth
        </div>
    </nav>
@endif

<div class="container-fluid">
    <div class="row">
        @yield('nav')
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="mt-3">
                @include('flash::message')
                @yield('breadcrumbs')
                @yield('content')
            </div>
        </main>
    </div>
</div>

{{--@livewireAssets--}}
<script>
    $('div.alert').not('.alert-important').delay(2000).fadeOut(350);
</script>
@stack('scripts')
</body>
</html>
