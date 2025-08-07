<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico" type="image/x-icon"/>
    <link href="{{asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('css/fa/css/all.css')}}" rel="stylesheet">
</head>
<body class="welcome-bg">
<nav class="navbar navbar-expand-md navbar-dark bg-nav fixed-top p-0">
    <a class="navbar-brand col-sm-3 mr-0" href="{{route('welcome2')}}">
        <img class="h-8 md:h-10 mr-2" src="{{asset('images/logo.svg')}}" alt="Materials Commons logo"/>
        Materials Commons 2.0
    </a>
    <div class="navbar-collapse collapse">

        <ul class="navbar-nav mr-auto">
            {{--            <li class="nav-item">--}}
            {{--                <a class="nav-link"--}}
            {{--                   href="{{route('welcome2')}}"--}}
            {{--                   style="color: #ffffff; font-weight: bold; font-size: 16px">--}}
            {{--                    Getting Started--}}
            {{--                </a>--}}
            {{--            </li>--}}
            <li class="nav-item">
                <a class="nav-link" href="{{route('welcome2')}}" style="font-size: 16px">Contact Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('welcome2')}}" style="font-size: 16px">About</a>
            </li>
        </ul>
        <ul class="navbar-nav mr-4 float-right">
            <li class="nav-item">
                <a class="nav-link mr-4" href="#" style="color: #ffffff; font-weight: bold; font-size: 17px">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" style="color: #ffffff; font-weight: bold; font-size: 17px">Sign Up!</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container" style="background: transparent">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <main role="main">

                <div class="text-center mb-4">
                    <h1 class="display-3 text-white font-serif">Materials Commons</h1>
                    {{--                    <h2 class="display-4 text-white-50 font-serif">Research Made Better</h2>--}}
                </div>
                @include('welcome._published-research')
                <br/>
                <div class="d-flex justify-content-center">
                    <a class="btn btn-primary btn-lg" id="abc" href="{{route('welcome2')}}" role="button">
                        <i class="fa fa-fw fa-book mr-2"></i>Getting Started Guide
                    </a>
                </div>
                <br/>
                <livewire:welcome.overview-image-switcher/>
                <br/>
                <br/>
                <br/>
                <br/>
            </main>
        </div>
    </div>
</div>
{{--<div style="background-color: #ffffff; height: 100vh; border-top-left-radius: 48px; border-top-right-radius: 48px;">--}}
{{--    <br/>--}}
{{--    <br/>--}}
{{--    <br/>--}}
{{--    <div class="row col-offset-3">--}}
{{--        <div class="col-5">--}}
{{--            <h4>Seamless Google Sheets Integration</h4>--}}
{{--            <ul>--}}
{{--                <li>Easily send your to google sheet to Materials Commons</li>--}}
{{--                <li>Make changes on Materials Commons and update your source of truth</li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--        <div class="col-7" style="background-color: #abdcff">--}}
{{--            <img src="{{asset('images/welcome/edit.jpg')}}"--}}
{{--                 class="img-fluid mt-4" alt="Responsive image"--}}
{{--                 style="border: 10px solid rgba(43, 107, 177, 0.3); border-radius: 8px"/>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
</body>
</html>
