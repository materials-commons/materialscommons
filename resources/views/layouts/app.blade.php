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

{{--    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">--}}

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
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('public.index')}}">Materials Commons 2.0</a>
    @else
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('projects.index')}}">
            <img class="h-8 md:h-10 mr-2" src="{{asset('images/logo.svg')}}" alt="Materials Commons logo"/> Materials
            Commons 2.0
        </a>
    @endif
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbar5">
        <ul class="navbar-nav">
            {{--            Kept here for formatting purposes--}}
            <li class="nav-item">
                {{--                <a class="nav-link outline-none td-none navbar-brand help-color"--}}
                {{--                   href="{{helpUrl()}}" target="_blank">--}}
                {{--                    Help--}}
                {{--                </a>--}}
                <a class="nav-link outline-none td-none navbar-brand help-color" data-toggle="modal" href="#item-help">
                    Help
                </a>
            </li>
        </ul>
        @auth
            @isset($project)
                <form method="post"
                      action="{{route('projects.search', [$project])}}"
                      class="mx-2 my-auto d-inline w-75">
                    @csrf
                    <input type="text"
                           class="form-control form-rounded border border-right-0"
                           placeholder="Search project..." name="search" aria-label="Search">
                </form>
            @else
                <form method="post"
                      action="{{route('projects.search_all')}}"
                      class="mx-2 my-auto d-inline w-75">
                    @csrf
                    <input type="text"
                           class="form-control form-rounded border border-right-0"
                           placeholder="Search across projects..." name="search" aria-label="Search">
                </form>
            @endisset
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle td-none outline-none" href="#" id="navbarDropdown"
                       role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{auth()->user()->name}}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <form method="post" action="{{route('logout')}}" id="signout">
                            @csrf
                            <a class="dropdown-item td-none" href="#"
                               onclick="document.getElementById('signout').submit()">
                                Sign out</a>
                        </form>
                    </div>
                </li>
            </ul>
        @else
            <form method="post"
                  action=""
                  class="mx-2 my-auto d-inline w-75">
                @csrf
                <input type="text"
                       class="form-control form-rounded border border-right-0"
                       placeholder="Search datasets..." name="search" aria-label="Search">
            </form>
            <ul class="navbar-nav">
                <li class="nav-item text-nowrap">
                    <a class="nav-link td-none" href="{{route('login')}}">Sign in/Register</a>
                </li>
            </ul>
        @endauth
    </div>
</nav>

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

<div class="modal fade" tabindex="-1" id="item-help" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Help for {{helpTitle()}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px">
                <iframe src="{{helpUrl()}}" width="100%" height="100%"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

{{--@livewireAssets--}}
<script>
    $('div.alert').not('.alert-important').delay(2000).fadeOut(350);
</script>
@stack('scripts')
</body>
</html>
