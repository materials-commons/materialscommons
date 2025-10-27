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
          href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"/>

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/fixedheader/4.0.4/css/fixedHeader.bootstrap5.min.css"/>

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.min.css"/>

    <script type="text/javascript"
            src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/fixedheader/4.0.4/js/dataTables.fixedHeader.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/fixedheader/4.0.4/js/fixedHeader.bootstrap5.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.min.js"></script>

    <script src="https://cdn.plot.ly/plotly-2.35.2.min.js" charset="utf-8"></script>

    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">


    {{--    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>--}}

    <link href="{{asset('css/fa/css/all.css')}}" rel="stylesheet">

    @stack('styles')

    {{--    @stack('')--}}

    <!-- Custom styles for this template -->
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-nav fixed-top p-0">
    @if(Request::routeIs('public.*'))
        <a class="navbar-brand col-sm-3 me-0 ps-3" href="{{route('welcome')}}">
            <img class="h-8 me-2" src="{{asset('images/logo.svg')}}" alt="Materials Commons logo"/>
            Materials Commons 2.0
        </a>
    @else
        <a class="navbar-brand col-sm-3 me-0 ps-3" href="{{route('welcome')}}">
            <img class="h-8 md:h-10 me-2" src="{{asset('images/logo.svg')}}" alt="Materials Commons logo"/>
            Materials Commons 2.0
        </a>
    @endif
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbar5">
        <ul class="navbar-nav">
            {{--            Kept here for formatting purposes--}}
            @auth
                <li class="nav-item">
                    <a class="nav-link outline-none td-none navbar-brand help-color cursor-pointer" id="app-start-tour">
                        <i class="fa fas fa-lightbulb tour-icon me-1"></i> Start Tour
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link outline-none td-none navbar-brand help-color" data-bs-toggle="modal"
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
                <a class="nav-link outline-none td-none navbar-brand help-color" data-bs-toggle="modal"
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
            <ul class="navbar-nav ps-4 pe-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle td-none outline-none" href="#" id="navbarDropdown"
                       role="button"
                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{auth()->user()->name}}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <form method="post" action="{{route('logout')}}" id="signout">
                            @csrf
                            <a class="dropdown-item td-none" href="#"
                               onclick="document.getElementById('signout').submit()">
                                Sign out</a>
                        </form>
                        <a class="dropdown-item td-none" href="{{route('accounts.show')}}">
                            <i class="fa-fw fas fa-user me-2 mb-1"></i>Account
                        </a>
                        {{--                        <a class="dropdown-item td-none" data-bs-toggle="modal" href="#project-setup">Welcome Dialog</a>--}}
                    </div>
                </li>
            </ul>
        @else
            <ul class="navbar-nav ps-4 pe-4">
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

        <main role="main" class="col-md-9 ms-md-auto col-lg-10 px-5 pb-4" stylex="padding-right: 7rem; padding-left: 7rem">
            @if(!is_null(config('app.banner')))
                <div class="mt-2 bg-red-5">
                    <p class="text-white pt-2 pb-2 ps-2 fs-14" style="text-align: center">
                        {{config('app.banner')}}
                    </p>
                </div>
            @endif

            <div class="mt-4">
                {{--                <x-table-container>--}}
                <livewire:force-livewire-load/>
                @include('flash::message')
                <div class="d-flex justify-content-center">
                    @yield('breadcrumbs')
                </div>
                {{--                <div class="row">--}}
                {{--                    <div class="col-10">--}}
                {{--                    @yield('breadcrumbs')--}}
                {{--                    </div>--}}
                {{--                    <div class="col-md-2" style="padding-top: 12px">--}}
                {{--                        <div class="float-end">--}}
                {{--                            Real time stuff here--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                @yield('content')
                {{--                </x-table-container>--}}
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
@include('app.dialogs._no-tour-dialog')
{{--@include('app.dialogs._copy-choose-project-dialog')--}}

<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mms-chtml.js"></script>
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

        @auth
        $('#app-start-tour').on('click', function () {
            window.tourService.initState("{{auth()->user()->api_token}}");

            // Get current route
            const currentRoute = "{{Route::currentRouteName()}}";

            // Get the appropriate tour for the current route
            const tourName = window.tourService.getTourForRoute(currentRoute);

            if (tourName) {
                // Start the tour
                window.tourService.startTour(tourName);
            } else {
                $('#no-tour-dialog').modal('show');
                console.error('No tour available for this page');
            }
        });
        @endauth
    });
    window.mc_grids = [];

</script>

@stack('scripts')
</body>
</html>
