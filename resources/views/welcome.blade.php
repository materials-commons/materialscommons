<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">

    <meta property="og:site_name" content=""/>
    <meta property="og:title" content=""/>
    <meta property="og:description" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:image" content="/assets/img/logo.png"/>
    <meta property="og:type" content="website"/>

    <meta name="twitter:image:alt" content="">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="google-site-verification" content="{{config('google.site-verification')}}"/>

    <title>Materials Commons</title>

    <link rel="home" href="/">
    <link rel="icon" href="/favicon.ico">

    @stack('meta')


    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i,800,800i"
          rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

</head>

<body class="flex flex-col justify-between min-h-screen bg-gray-100 text-gray-800 leading-normal font-sans">
<header class="flex items-center shadow bg-white border-b h-18 py-4 bg-blue-700" role="banner">
    <div class="container flex items-center max-w-8xl mx-auto px-4 lg:px-8">
        <div class="flex items-center">
            <a href="{{route('welcome')}}" title="Materials Commons home" class="inline-flex items-center">
                <img class="h-8 md:h-10 mr-3" src="{{asset('images/logo.svg')}}" alt="Materials Commons logo"/>

                <h1 class="text-lg md:text-2xl text-yellow-100 font-semibold hover:text-yellow-100 my-0 pr-4">
                    Materials Commons 2.0
                </h1>
            </a>
        </div>


        <div class="flex flex-1 justify-end items-center text-right md:pl-10">
            @auth
                <a href="{{route('dashboard')}}"
                   class="flex justify-center items-center text-blue-300 h-10 mr-4 px-5 focus:outline-none hover:text-blue-100">
                    {{auth()->user()->email}}
                </a>
                <a href="{{route('dashboard')}}"
                   class="flex justify-center items-center text-blue-300 h-10 mr-4 px-5 focus:outline-none hover:text-blue-100">
                    Goto Dashboard
                </a>
            @else
                <a href="{{route('login')}}"
                   class="flex justify-center items-center text-blue-300 h-10 mr-4 px-5 focus:outline-none hover:text-blue-100">
                    Login
                </a>
                <a href="{{route('register')}}"
                   class="flex justify-center items-center text-blue-300 h-10 mr-4 px-5 focus:outline-none hover:text-blue-100">
                    Register
                </a>
            @endauth
        </div>
    </div>

    @yield('nav-toggle')
</header>

<main role="main" class="w-full flex-auto">
    <section class="container max-w-6xl mx-auto px-6 py-4 md:py-6">
        <div class="flex flex-col-reverse mb-6 lg:flex-row lg:mb-6">
            <div class="mt-4">
                <h1 id="intro-docs-template">Materials Commons 2.0</h1>

                <h2 id="intro-powered-by-jigsaw" class="font-light mt-4">A site for Materials Scientists to collaborate,
                    store and publish research.</h2>

                <p class="text-lg">Give your research a boost with Materials Commons. <br class="hidden sm:block"> Store
                    your results and workflow. Easily share with colleagues or publish online.</p>

                <div class="flex my-10">


                    <a href="{{makeHelpUrl("getting-started")}}" title="About Materials Commons"
                       title="Materials Commons getting started"
                       class="bg-blue-500 hover:bg-blue-600 font-normal text-white hover:text-white rounded mr-4 py-2 px-6">
                        Getting Started
                    </a>

                    @auth
                        <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
                           title="Publish data"
                           class="bg-blue-500 hover:bg-blue-600 font-normal text-white hover:text-white rounded mr-4 py-2 px-6">
                            Upload and Publish Data
                        </a>
                    @else
                        <a href="{{route('login-for-upload')}}" title="Publish data"
                           class="bg-blue-500 hover:bg-blue-600 font-normal text-white hover:text-white rounded mr-4 py-2 px-6">
                            Upload and Publish Data
                        </a>
                    @endauth

                    <a href="{{route('public.index')}}" title="Browse published data"
                       class="bg-blue-500 hover:bg-blue-600 font-normal text-white hover:text-white rounded mr-4 py-2 px-6">
                        Browse Published Data
                    </a>

                    {{--                    <a href="{{route('about')}}" title="About Materials Commons"--}}
                    {{--                       class="bg-gray-400 hover:bg-gray-600 text-blue-900 font-normal hover:text-white rounded py-2 px-6">--}}
                    {{--                        About Materials Commons--}}
                    {{--                    </a>--}}

                </div>
            </div>

            <img src="{{asset('images/logo-large.svg')}}" alt="Materials Commons large logo"
                 class="mx-auto mb-6 lg:mb-0 ">
        </div>

        <p>
            Materials Commons has as an extensive CLI and API. Learn more about:
        </p>
        <ul class="ml-10">
            <li><a href="#">Using Jupyter Notebooks with Materials Commons</a></li>
            <li><a href="#">Getting Started with the Materials Commons CLI</a></li>
            <li><a href="#">Materials Commons API Documentation</a></li>
        </ul>

        <hr class="block my-8 border lg:hidden">

        <div class="md:flex -mx-2 -mx-4">
            <div class="mb-8 mx-3 px-2 md:w-1/3">
                <img src="{{asset('images/icon-window.svg')}}" class="h-12 w-12" alt="window icon">

                <h3 id="intro-laravel" class="text-2xl text-blue-900 mb-0">Access Anywhere</h3>

                <p>The Materials Commons website gives you access to your research anywhere you have an internet
                    connection.</p>
            </div>

            {{--            <div class="mb-8 mx-3 px-2 md:w-1/3">--}}
            {{--                <img src="{{asset('images/icon-terminal.svg')}}" class="h-12 w-12" alt="terminal icon">--}}

            {{--                <h3 id="intro-markdown" class="text-2xl text-blue-900 mb-0">Powerful Command Line Tool</h3>--}}

            {{--                <p>If you prefer to work from the command prompt we've got you covered! Materials Commons comes with a--}}
            {{--                    powerful command line tool and a Python API that allows you to write scripts to automate--}}
            {{--                    processes. </p>--}}
            {{--            </div>--}}

            <div class="mx-3 px-2 md:w-1/3">
                <img src="{{asset('images/icon-stack.svg')}}" class="h-12 w-12" alt="stack icon">

                <h3 id="intro-mix" class="text-2xl text-blue-900 mb-0">Store. Share. Publish.</h3>

                <p>Storing your data on Materials Commons gives you access to tools for annotating your data, sharing it
                    with others, and publishing
                    datasets the community can easily find.</p>
            </div>
        </div>
    </section>
</main>

<script src="{{ asset('js/main.js') }}"></script>

@stack('scripts')

<footer class="bg-white text-center text-sm mt-12 py-4" role="contentinfo">
    <div class="flex flex-col md:flex-row justify-center">
        <span class="md:ml-4">
            The Materials Commons is supported by the U.S. Department of Energy, Office of Basic Energy Sciences,
            Division of Materials Sciences and Engineering under Award #DE-SC0008637 as part of the
            <a href="http://www.prisms-center.org" target="_blank">
                Center for PRedictive Integrated Structural Materials Science (PRISMS Center)
            </a> at the University of Michigan.
        </span>
    </div>
</footer>
</body>
</html>
