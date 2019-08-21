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
<nav class="navbar navbar-dark fixed-top bg-nav p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('projects.index')}}">MaterialsCommons</a>
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
        @yield('nav')
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="mt-3">
                @yield('content')
            </div>
        </main>
    </div>
</div>

@livewireAssets

@stack('scripts')
</body>
</html>
