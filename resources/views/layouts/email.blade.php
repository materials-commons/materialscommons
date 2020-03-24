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
<div class="container-fluid">
    <div class="row">
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="mt-3">
                @yield('content')
            </div>
        </main>
    </div>
</div>
</body>
</html>