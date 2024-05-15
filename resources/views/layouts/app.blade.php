<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(Request::routeIs('public.datasets.show'))
        <META NAME="ROBOTS" CONTENT="NOFOLLOW">
    @elseif(Request::routeIs('public.datasets.*'))
        <META NAME="ROBOTS" CONTENT="NOINDEX">
    @endif

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

    @stack('googleds')


    {{--    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">--}}
    {{--    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">--}}

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css"/>

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css"/>

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.bootstrap4.css"/>

    <script type="text/javascript"
            src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>

    <link href="{{asset('css/fa/css/all.css')}}" rel="stylesheet">

    @stack('styles')

    {{--    @stack('')--}}

    <!-- Custom styles for this template -->
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-nav fixed-top p-0">
    @if(Request::routeIs('public.*'))
        <a class="navbar-brand col-sm-3 mr-0" href="{{route('welcome')}}">
            <img class="h-8 mr-2" src="{{asset('images/logo.svg')}}" alt="Materials Commons logo"/>
            Materials Commons 2.0
        </a>
    @else
        <a class="navbar-brand col-sm-3 mr-0" href="{{route('welcome')}}">
            <img class="h-8 md:h-10 mr-2" src="{{asset('images/logo.svg')}}" alt="Materials Commons logo"/>
            Materials Commons 2.0
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
                <a class="nav-link outline-none td-none navbar-brand help-color" data-toggle="modal"
                   href="#jupyter-dialog">
                    Jupyter
                </a>
            </li>
            @auth
                <li class="nav-item">
                    <a class="nav-link outline-none td-none navbar-brand help-color" data-toggle="modal"
                       href="#code-dialog">
                        CLI/API
                    </a>
                </li>
            @endauth
            <li class="nav-item">
                {{--                <a class="nav-link outline-none td-none navbar-brand help-color"--}}
                {{--                   href="{{helpUrl()}}" target="_blank">--}}
                {{--                    Help--}}
                {{--                </a>--}}
                <a class="nav-link outline-none td-none navbar-brand help-color" data-toggle="modal"
                   href="#help-dialog">
                    Help
                </a>
            </li>
            @if(!is_null(config('app.survey_url')))
                <li class="nav-item">
                    <a class="nav-link outline-none td-none navbar-brand cursor-pointer font-italic"
                       href="{{config('app.survey_url')}}"
                       target="_blank">
                        <span class="yellow-7 italic">Take our survey!</span>
                    </a>
                </li>
            @endif
        </ul>
        @include('partials.navbar._search')
        @auth
            <ul class="navbar-nav pl-4 pr-4">
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
                        {{--                        <a class="dropdown-item td-none" data-toggle="modal" href="#project-setup">Welcome Dialog</a>--}}
                    </div>
                </li>
            </ul>
        @else
            <ul class="navbar-nav pl-4 pr-4">
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
            @if(!is_null(config('app.banner')))
                <div class="mt-2 bg-red-5">
                    <p class="text-white pt-2 pb-2 pl-2 fs-14">
                        {{config('app.banner')}}
                    </p>
                </div>
            @endif

            <div class="mt-3">
                @include('flash::message')
                @yield('breadcrumbs')
                @yield('content')
            </div>
        </main>
    </div>
</div>

@include('app.dialogs._jupyter-dialog')
@auth
    @include('app.dialogs._code-dialog')
@endauth
@include('app.dialogs._help-dialog')
@include('app.dialogs._welcome-dialog')
{{--@include('app.dialogs._copy-choose-project-dialog')--}}

<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
    MathJax = {
        tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']]
        },
        svg: {
            fontCache: 'global'
        }
    };
    // $('div.alert').not('.alert-important').delay(2000).fadeOut(350);
    $(document).ready(() => {
        mcutil.autosizeTextareas();
    });
    window.mc_grids = [];

    // function closeSearch() {
    //     $('#search-project-input').val('');
    // }
</script>

@stack('scripts')
</body>
</html>
