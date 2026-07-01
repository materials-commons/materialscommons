<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="Materials Commons — store, share, and publish materials science research data.">

    <meta property="og:site_name" content="Materials Commons"/>
    <meta property="og:title" content="Materials Commons"/>
    <meta property="og:description" content="Store, share, and publish materials science research data."/>
    <meta property="og:url" content=""/>
    <meta property="og:image" content="/assets/img/logo.png"/>
    <meta property="og:type" content="website"/>

    <meta name="twitter:image:alt" content="Materials Commons">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="google-site-verification" content="{{ config('google.site-verification') }}"/>

    <title>Materials Commons</title>

    <link rel="home" href="/">
    <link rel="icon" href="/favicon.ico">

    @stack('meta')

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .mc-navbar-brand {
            transition: opacity .15s;
        }

        .mc-navbar-brand:hover {
            opacity: .85;
        }

        .mc-hero {
            position: relative;
            overflow: visible;
            background:
                radial-gradient(circle at 88% 18%, rgba(43,107,177,.12) 0, rgba(43,107,177,.05) 30%, transparent 52%),
                linear-gradient(180deg, #ffffff 0%, #f7fafd 100%);
            border-bottom: 1px solid #e2ebf5;
        }

        .mc-hero-content {
            position: relative;
            z-index: 1;
        }

        .mc-hero-title {
            color: #173f6f;
        }

        .mc-hero-copy {
            color: #4f647a;
        }

        .mc-hero-small-copy {
            color: #5d7186;
        }

        .mc-hero-logo-panel {
            position: relative;
            background: #ffffff;
            border: 1px solid #dce7f2;
            border-radius: 1.25rem;
            box-shadow: 0 1rem 2.25rem rgba(26,79,138,.14);
        }

        .mc-hero-logo {
            display: block;
            width: min(100%, 260px);
            max-height: 230px;
            object-fit: contain;
            opacity: 1;
            filter: none;
        }

        .mc-logo-caption {
            color: #315f8f;
        }

        .mc-cta-row {
            gap: .75rem;
        }

        .mc-cta-row .btn {
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            .mc-cta-row {
                flex-wrap: nowrap !important;
            }
        }

        .mc-kicker {
            display: inline-flex;
            align-items: center;
            color: #1e4f8a;
            background: #eaf3fc;
            border: 1px solid #cfe0f2;
            border-radius: 999px;
            letter-spacing: .12em;
            padding: .35rem .75rem;
        }

        .mc-stats-band {
            background: linear-gradient(160deg, #1a4f8a 0%, #2b6bb1 60%, #3a82d4 100%);
            border-radius: 1rem;
            box-shadow: 0 .85rem 1.75rem rgba(26,79,138,.18);
        }

        .mc-stat-divider {
            border-left: 1px solid rgba(255,255,255,.25);
        }

        @media (max-width: 767.98px) {
            .mc-stat-divider {
                border-left: 0;
                border-top: 1px solid rgba(255,255,255,.18);
            }
        }

        .mc-features-section {
            background: #ffffff;
        }

        .mc-feature-card {
            border-top: 3px solid #2b6bb1;
            transition: box-shadow .15s, transform .15s;
        }

        .mc-feature-card:hover {
            box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1) !important;
            transform: translateY(-2px);
        }

        .mc-icon-circle {
            background: rgba(13,110,253,.08);
            width: 3.2rem;
            height: 3.2rem;
        }

        .mc-original-note {
            border-left: 4px solid #2b6bb1;
            background: #f8fbff;
            border-top: 1px solid #e3ebf4;
            border-right: 1px solid #e3ebf4;
            border-bottom: 1px solid #e3ebf4;
        }

        .btn-primary {
            background-color: #1e4f8a;
            border-color: #1e4f8a;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #173f6f;
            border-color: #173f6f;
        }

        .btn-outline-primary {
            color: #1e4f8a;
            border-color: #1e4f8a;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            color: #ffffff;
            background-color: #1e4f8a;
            border-color: #1e4f8a;
        }

        /* Fix outline-light hover — Bootstrap defaults can be overridden by app.css */
        .btn-outline-light:hover,
        .btn-outline-light:focus {
            color: #1e4f8a !important;
            background-color: #ffffff !important;
            border-color: #ffffff !important;
        }
    </style>

</head>

<body class="d-flex flex-column min-vh-100 bg-light">

{{-- Banner --}}
@if(!is_null(config('app.banner')))
    <div class="bg-danger text-white text-center py-2">
        <p class="mb-0">{{ config('app.banner') }}</p>
    </div>
@endif

{{-- Navbar --}}
<nav class="navbar py-3 shadow-sm" style="background-color:#1e4f8a;" role="banner">
    <div class="container-xl d-flex align-items-center">
        <a href="{{ route('welcome') }}" title="Materials Commons home"
           class="mc-navbar-brand d-flex align-items-center text-decoration-none">
            <img src="{{ asset('images/logo.svg') }}" alt="Materials Commons logo"
                 style="height:2.5rem;" class="me-3">
            <span class="text-white fw-semibold fs-4">Materials Commons 2.0</span>
        </a>

        <div class="ms-auto d-flex align-items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="text-white text-opacity-75 text-decoration-none small d-none d-md-inline">
                    {{ auth()->user()->email }}
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="text-white text-opacity-75 text-decoration-none px-2">
                    Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- Main --}}
<main role="main" class="flex-grow-1">

    {{-- Hero --}}
    <section class="mc-hero py-5">
        <div class="container mc-hero-content">
            <div class="row align-items-center g-4">

                <div class="col-12 col-lg-8 text-center text-lg-start">
                    <p class="fw-semibold mb-3 small text-uppercase mc-kicker">
                        Materials Commons 2.0
                    </p>

                    <h1 class="fw-bold display-5 mb-3 mc-hero-title">
                        Store, share, and publish materials science research
                    </h1>

                    <p class="lead mb-4 mc-hero-copy" style="max-width:650px;">
                        Give your research a boost with Materials Commons. Create project spaces,
                        organize research data and workflows, collaborate with colleagues, and publish
                        DOI-citable datasets for the community.
                    </p>

                    <div class="mc-cta-row d-flex flex-wrap justify-content-center justify-content-lg-start mb-4">
                        <a href="{{ route('public.index') }}" class="btn btn-primary px-3 px-xl-4">
                            <i class="fas fa-database me-2"></i>Browse Data
                        </a>

                        @auth
                            <a href="{{ route('public.publish.wizard.choose_create_or_select_project') }}"
                               class="btn btn-outline-primary px-3 px-xl-4">
                                <i class="fas fa-upload me-2"></i>Upload &amp; Publish
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-outline-primary px-3 px-xl-4">
                                <i class="fas fa-user-plus me-2"></i>Register Free
                            </a>
                        @endauth

                        <a href="/mcdocs2" class="btn btn-outline-primary px-3 px-xl-4">
                            <i class="fas fa-book-open me-2"></i>Getting Started
                        </a>
                    </div>

                    <p class="mb-0 small mc-hero-small-copy">
                        Free project space and publishing for researchers working with computational
                        and experimental materials data.
                    </p>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="mc-hero-logo-panel p-3 p-xl-4 text-center mx-auto" style="max-width:340px;">
                        <img src="{{ asset('images/logo-large.svg') }}"
                             alt="Materials Commons large logo"
                             class="mc-hero-logo mx-auto mb-3">
                        <div class="mc-logo-caption small fw-semibold">
                            A trusted home for materials science research data.
                        </div>
                    </div>
                </div>

            </div>

            {{-- Live stats --}}
            <div class="mt-5">
                <div class="mc-stats-band row g-0 justify-content-center text-white overflow-hidden">
                    <div class="col-6 col-md-3 px-4 py-3 text-center">
                        <div class="fw-bold fs-4">{{ number_format($publishedDatasetsCount) }}</div>
                        <div class="text-white text-opacity-75 small">Datasets</div>
                    </div>
                    <div class="col-6 col-md-3 px-4 py-3 text-center mc-stat-divider">
                        <div class="fw-bold fs-4">2</div>
                        <div class="text-white text-opacity-75 small">Special Collections</div>
                    </div>
                    <div class="col-6 col-md-3 px-4 py-3 text-center mc-stat-divider">
                        <div class="fw-bold fs-4">{{ number_format($projectsCount) }}</div>
                        <div class="text-white text-opacity-75 small">Research Projects</div>
                    </div>
                    <div class="col-6 col-md-3 px-4 py-3 text-center mc-stat-divider">
                        <div class="fw-bold fs-4">{{ number_format($usersCount) }}</div>
                        <div class="text-white text-opacity-75 small">Registered Researchers</div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    {{-- Feature cards --}}
    <section class="mc-features-section border-bottom py-5">
        <div class="container">
            <div class="row g-4">

                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100 mc-feature-card">
                        <div class="card-body p-4">
                            <div class="d-inline-flex align-items-center justify-content-center
                                        rounded-circle mb-3 mc-icon-circle">
                                <i class="fas fa-globe text-primary" style="font-size:1.2rem;"></i>
                            </div>
                            <h3 class="fs-5 fw-bold mb-2">Access Anywhere</h3>
                            <p class="text-muted mb-0">
                                Materials Commons gives you access to your research anywhere you have
                                an internet connection. Keep files, metadata, processes, and project
                                context together in one place.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100 mc-feature-card">
                        <div class="card-body p-4">
                            <div class="d-inline-flex align-items-center justify-content-center
                                        rounded-circle mb-3 mc-icon-circle">
                                <i class="fas fa-chart-bar text-primary" style="font-size:1.2rem;"></i>
                            </div>
                            <h3 class="fs-5 fw-bold mb-2">Analytics &amp; Impact Tracking</h3>
                            <p class="text-muted mb-0">
                                Built-in analytics give you insight into your project data — attribute
                                coverage, process distributions, and file metrics. Track the real-world
                                impact of your published datasets.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100 mc-feature-card">
                        <div class="card-body p-4">
                            <div class="d-inline-flex align-items-center justify-content-center
                                        rounded-circle mb-3 mc-icon-circle">
                                <i class="fas fa-share-alt text-primary" style="font-size:1.2rem;"></i>
                            </div>
                            <h3 class="fs-5 fw-bold mb-2">Store. Share. Publish.</h3>
                            <p class="text-muted mb-0">
                                Annotate your data, collaborate with colleagues, and publish datasets
                                the community can easily find and cite. Supports both computational and
                                experimental materials data.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-5">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="mc-original-note rounded-3 p-4">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md">
                                <h2 class="h5 fw-bold mb-2">A research workspace built for materials scientists</h2>
                                <p class="text-muted mb-0">
                                    Store your results and workflow, easily share with collaborators,
                                    and publish online when your dataset is ready.
                                </p>
                            </div>
                            <div class="col-12 col-md-auto">
                                @auth
                                    <a href="{{ route('public.publish.wizard.choose_create_or_select_project') }}"
                                       class="btn btn-primary">
                                        Upload and Publish Data
                                    </a>
                                @else
                                    <a href="{{ route('login-for-upload') }}"
                                       class="btn btn-primary">
                                        Upload and Publish Data
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- CLI/API section --}}
    <section class="container py-5">

        <div class="row align-items-start g-5">

            <div class="col-12 col-lg-5">
                <div class="d-inline-flex align-items-center justify-content-center
                            rounded-circle mb-3 mc-icon-circle">
                    <i class="fas fa-terminal text-primary" style="font-size:1.2rem;"></i>
                </div>
                <h2 class="h4 fw-bold mb-2">Integrate with your workflow</h2>
                <p class="text-muted">
                    Materials Commons has an extensive CLI and Python API — write scripts to automate
                    processes and connect your research pipeline directly to the platform.
                </p>
                <a href="/mcdocs2" class="btn btn-outline-primary mt-1">
                    Getting Started <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="col-12 col-lg-7">
                <div class="list-group list-group-flush">
                    <a href="https://materials-commons.github.io/materials-commons-cli/html/manual/jupyter.html"
                       target="_blank"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-0 border-0 border-bottom">
                        <i class="fab fa-python text-primary fs-5 flex-shrink-0"></i>
                        <div>
                            <div class="fw-semibold">Using Jupyter Notebooks with Materials Commons</div>
                            <div class="text-muted small">Integrate your notebook workflow with MC storage and data</div>
                        </div>
                        <i class="fas fa-external-link-alt text-muted ms-auto small"></i>
                    </a>

                    <a href="https://materials-commons.github.io/materials-commons-cli/html/index.html"
                       target="_blank"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-0 border-0 border-bottom">
                        <i class="fas fa-terminal text-primary fs-5 flex-shrink-0"></i>
                        <div>
                            <div class="fw-semibold">Getting Started with the Materials Commons CLI</div>
                            <div class="text-muted small">Command-line tools for uploading, downloading, and managing data</div>
                        </div>
                        <i class="fas fa-external-link-alt text-muted ms-auto small"></i>
                    </a>

                    <a href="https://materials-commons.github.io/materials-commons-api/html/index.html"
                       target="_blank"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-0 border-0">
                        <i class="fas fa-book text-primary fs-5 flex-shrink-0"></i>
                        <div>
                            <div class="fw-semibold">Materials Commons Python API Documentation</div>
                            <div class="text-muted small">Full API reference for programmatic access to all features</div>
                        </div>
                        <i class="fas fa-external-link-alt text-muted ms-auto small"></i>
                    </a>
                </div>
            </div>

        </div>

    </section>

</main>

<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

{{-- Footer --}}
<footer class="bg-white border-top small mt-auto py-4" role="contentinfo">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="fw-semibold mb-2 text-dark">Quick Links</div>
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="{{ route('public.index') }}" class="text-muted text-decoration-none">
                            Browse Published Data
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="text-muted text-decoration-none">
                            Register
                        </a>
                    </li>
                    <li>
                        <a href="/mcdocs2" class="text-muted text-decoration-none">
                            Documentation
                        </a>
                    </li>
                    <li>
                        <a href="https://materials-commons.github.io/materials-commons-api/html/index.html"
                           target="_blank" class="text-muted text-decoration-none">
                            Python API
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-12 col-md-8 text-muted">
                <div class="fw-semibold mb-2 text-dark">Acknowledgement</div>
                The Materials Commons is supported by the U.S. Department of Energy, Office of
                Basic Energy Sciences, Division of Materials Sciences and Engineering under Award
                #DE-SC0008637 as part of the
                <a href="http://www.prisms-center.org" target="_blank">
                    Center for PRedictive Integrated Structural Materials Science (PRISMS Center)
                </a> at the University of Michigan.
            </div>
        </div>
    </div>
</footer>

</body>
</html>
