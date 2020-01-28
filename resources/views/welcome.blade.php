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

    <title>MaterialsCommons</title>

    <link rel="home" href="/">
    <link rel="icon" href="/favicon.ico">

    @stack('meta')


    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i,800,800i"
          rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

</head>

<body class="flex flex-col justify-between min-h-screen bg-gray-100 text-gray-800 leading-normal font-sans">
<header class="flex items-center shadow bg-white border-b h-24 mb-8 py-4" role="banner">
    <div class="container flex items-center max-w-8xl mx-auto px-4 lg:px-8">
        <div class="flex items-center">
            <a href="/" title="Materials Commons home" class="inline-flex items-center">
                <img class="h-8 md:h-10 mr-3" src="{{asset('images/logo.svg')}}" alt="Materials Commons logo"/>

                <h1 class="text-lg md:text-2xl text-blue-900 font-semibold hover:text-blue-600 my-0 pr-4">Materials
                    Commons 2.0</h1>
            </a>
        </div>

        <div class="flex flex-1 justify-end items-center text-right md:pl-10">
            <a href="/login"
               class="flex justify-center items-center bg-blue-500 text-white border border-blue-500 h-10 mr-4 px-5 rounded-full focus:outline-none">
                Login
            </a>
            <a href="/register"
               class="flex justify-center items-center bg-blue-500 text-white border border-blue-500 h-10 mr-4 px-5 rounded-full focus:outline-none">
                Register
            </a>
        </div>
    </div>

    @yield('nav-toggle')
</header>

<main role="main" class="w-full flex-auto">
    <section class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
        <div class="flex flex-col-reverse mb-10 lg:flex-row lg:mb-24">
            <div class="mt-8">
                <h1 id="intro-docs-template">Materials Commons</h1>

                <h2 id="intro-powered-by-jigsaw" class="font-light mt-4">A site for Materials Scientists to collaborate,
                    store and publish research.</h2>

                <p class="text-lg">Give your research a boost with MaterialsCommons. <br class="hidden sm:block">Track
                    your assets and workflow. Easily share with colleagues or publish online.</p>

                <div class="flex my-10">
                    <a href="#" title="About MaterialsCommons"
                       class="bg-blue-500 hover:bg-blue-600 font-normal text-white hover:text-white rounded mr-4 py-2 px-6">
                        View Published Datasets
                    </a>

                    <a href="/docs/getting-started" title="Materials Commons getting started"
                       class="bg-blue-500 hover:bg-blue-600 font-normal text-white hover:text-white rounded mr-4 py-2 px-6">
                        Read the docs
                    </a>

                    <a href="#" title="About MaterialsCommons"
                       class="bg-gray-400 hover:bg-gray-600 text-blue-900 font-normal hover:text-white rounded py-2 px-6">
                        About Materials Commons
                    </a>


                </div>
            </div>

            <img src="{{asset('images/logo-large.svg')}}" alt="Materials Commons large logo"
                 class="mx-auto mb-6 lg:mb-0 ">
        </div>

        <hr class="block my-8 border lg:hidden">

        <div class="md:flex -mx-2 -mx-4">
            <div class="mb-8 mx-3 px-2 md:w-1/3">
                <img src="{{asset('images/icon-window.svg')}}" class="h-12 w-12" alt="window icon">

                <h3 id="intro-laravel" class="text-2xl text-blue-900 mb-0">Access Anywhere</h3>

                <p>The MaterialsCommons website gives you access to your research anywhere you have an internet
                    connection.</p>
            </div>

            <div class="mb-8 mx-3 px-2 md:w-1/3">
                <img src="{{asset('images/icon-terminal.svg')}}" class="h-12 w-12" alt="terminal icon">

                <h3 id="intro-markdown" class="text-2xl text-blue-900 mb-0">Powerful Command Line Tool</h3>

                <p>MaterialsCommons comes with a powerful command line tool that allows you to write scripts. If you
                    prefer to work
                    from the prompt we got you covered!</p>
            </div>

            <div class="mx-3 px-2 md:w-1/3">
                <img src="{{asset('images/icon-stack.svg')}}" class="h-12 w-12" alt="stack icon">

                <h3 id="intro-mix" class="text-2xl text-blue-900 mb-0">Save. Share. Publish.</h3>

                <p>Saving your data on MaterialsCommons gives you access to tools for sharing with others, publishing
                    your results and
                    protecting your important assets.</p>
            </div>
        </div>
    </section>
</main>

<script src="{{ asset('js/main.js') }}"></script>

@stack('scripts')

<footer class="bg-white text-center text-sm mt-12 py-4" role="contentinfo">
    <ul class="flex flex-col md:flex-row justify-center">
        <li class="md:mr-2">
            &copy; <a href="https://prisms-center.org" title="PRISMS Website">PRISMS Center</a> {{ date('Y') }}.
        </li>

        <li class="md:ml-4">
            Built with <a href="http://jigsaw.tighten.co" title="Jigsaw by Tighten">Jigsaw</a>
            and <a href="https://tailwindcss.com" title="Tailwind CSS, a utility-first CSS framework">Tailwind CSS</a>.
        </li>
    </ul>
</footer>
</body>
</html>
