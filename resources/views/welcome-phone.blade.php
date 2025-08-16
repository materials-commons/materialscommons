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

    {{--    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">--}}
    {{--    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">--}}

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{asset('css/fa/css/all.css')}}" rel="stylesheet">

    @stack('styles')

    <!-- Your existing head content -->
    <style>
        body, html {
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
        }

        .container-fluid {
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* Prevent select picker from causing layout issues */
        .bootstrap-select .dropdown-menu {
            max-width: 100vw;
        }

        .bootstrap-select {
            width: 100% !important;
        }
    </style>

    {{--    @stack('')--}}

    <!-- Custom styles for this template -->
</head>

<body style="background-color: #ffffff">
<div class="container-fluid">
    <div class="row">
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

            <div class="mt-3">
                <h4 class="text-center">Materials Commons</h4>
                @auth
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-3 w-100">
                            <input type="file" id="backCamera" accept="image/*" capture="environment"
                                   style="display: none;" disabled>
                            <a class="btn btn-success w-100 text-center"
                               href="#"
                               style="opacity: 0.6; pointer-events: none;"
                               id="cameraButton"
                               onclick="document.getElementById('backCamera').click();">
                                <i class="fas fa-camera mr-2"></i>Take Picture
                            </a>
                        </div>
                        <div class="mb-3 w-100">
                            <select class="selectpicker w-100" id="select-project" data-live-search="true"
                                    data-style="btn-light no-tt"
                                    title="Select Project For Pictures">
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 w-100">
                            <a class="btn btn-success w-100 text-center" href="{{route('dashboard')}}">
                                <i class="fas fa-tachometer-alt mr-2"></i>Goto Dashboard
                            </a>
                        </div>
                        <div class="mb-3 w-100">
                            <a class="btn btn-success w-100 text-center" href="{{route('public.index')}}">
                                <i class="fas fa-globe mr-2"></i>Goto Published Datasets
                            </a>
                        </div>

                        <div class="mb-3 w-100">
                            <a class="btn btn-danger w-100 text-center" href="{{route('logout-get')}}">
                                <i class="fas fa-globe mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                @else
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-3 w-100">
                            <a class="btn btn-success w-100 text-center" href="{{route('login')}}">
                                <i class="fas fa-user mr-2"></i>Login
                            </a>
                        </div>
                        <div class="mb-3 w-100">
                            <a class="btn btn-success w-100 text-center" href="{{route('public.index')}}">
                                <i class="fas fa-globe mr-2"></i>Goto Published Datasets
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </main>
    </div>
</div>

<script>

    $(document).ready(() => {
        // Initially disable the camera button and refresh the select dropdown.

        $('#select-project').val('').selectpicker('refresh');
        $('#backCamera').prop('disabled', true);
        $('#cameraButton').css({
            'opacity': '0.6',
            'pointer-events': 'none'
        });

        $('#select-project').on('change', function () {
            let selected = $(this).val();
            if (selected && selected !== '') {
                // Enable camera button
                $('#backCamera').prop('disabled', false);
                $('#cameraButton').css({
                    'opacity': '1',
                    'pointer-events': 'auto'
                });
            } else {
                // Disable the camera button if no selection
                $('#backCamera').prop('disabled', true);
                $('#cameraButton').css({
                    'opacity': '0.6',
                    'pointer-events': 'none'
                });
            }
        });
    });

    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                uploadPhoto(file);
            }
        });
    });

    async function uploadPhoto(file) {
        let projectId = $('#select-project').val();
        @auth
        let apiToken = "{{auth()->user()->api_token}}";
        @else
        let apiToken = "";
        @endauth
        const formData = new FormData();
        formData.append('file', file);
        formData.append('project_id', projectId);

        let r = route('api.upload-camera-image', {api_token: apiToken});

        try {
            const response = await fetch(r, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                const errorText = await response.text();
                alert(`Upload failed: ${response.status} - ${errorText}`);
            } else {
                let data = await response.json();
                alert(`Upload successful`);
            }
        } catch (error) {
            alert('Upload error');
        }
    }
</script>
</body>
</html>
