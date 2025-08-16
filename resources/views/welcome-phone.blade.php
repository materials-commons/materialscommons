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
                <div class="pt-2 pb-1 mb-4" style="background-color: #3b7ad8; border-radiusx: 8px">
                    <h4 class="text-center" style="color: #ffffff">Materials Commons</h4>
                </div>
                @auth
                    <div class="d-flex flex-column align-items-center">
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
                            <input type="file" id="photoGallery" accept="image/*" multiple
                                   style="display: none;" disabled>
                            <a class="btn btn-primary w-100 text-center"
                               href="#"
                               style="opacity: 0.6; pointer-events: none;"
                               id="galleryButton"
                               onclick="document.getElementById('photoGallery').click();">
                                <i class="fas fa-images mr-2"></i>Choose from Gallery
                            </a>
                        </div>

                        <hr style="width: 100%; border-color: #dee2e6;"/>

                        <div class="mb-3 w-100" style="margin-top: 20px">
                            <a class="btn btn-success w-100 text-center" href="/mcdocs2/">
                                <i class="fas fa-book mr-2"></i>Goto Documentation
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
                        <div class="mb-4 w-100">
                            <div class="card border-light" style="background-color: #f8f9fa;">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-camera text-muted" style="font-size: 3rem;"></i>
                                        <i class="fas fa-images text-muted ml-2" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="card-title text-muted mb-3">Photo Upload Features</h5>
                                    <p class="card-text text-muted mb-3">
                                        Take photos with your camera or choose multiple images from your gallery to
                                        upload to your projects.
                                    </p>
                                    <div class="alert alert-info mb-0" role="alert">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Login required</strong> to access photo upload features
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 w-100">
                            <a class="btn btn-success w-100 text-center" href="{{route('login')}}">
                                <i class="fas fa-user mr-2"></i>Login To Upload Photos
                            </a>
                        </div>

                        <div class="mb-3 w-100">
                            <a class="btn btn-success w-100 text-center" href="/mcdocs2/">
                                <i class="fas fa-book mr-2"></i>Goto Documentation
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </main>
    </div>
</div>

<script>

    function disableCameraButton() {
        $('#backCamera').prop('disabled', true);
        $('#cameraButton').css({
            'opacity': '0.6',
            'pointer-events': 'none'
        });
    }

    function enableCameraButton() {
        $('#backCamera').prop('disabled', false);
        $('#cameraButton').css({
            'opacity': '1',
            'pointer-events': 'auto'
        });
    }

    function disableGalleryButton() {
        $('#photoGallery').prop('disabled', true);
        $('#galleryButton').css({
            'opacity': '0.6',
            'pointer-events': 'none'
        });
    }

    function enableGalleryButton() {
        $('#photoGallery').prop('disabled', false);
        $('#galleryButton').css({
            'opacity': '1',
            'pointer-events': 'auto'
        });
    }

    $(document).ready(() => {
        // Initially disable the camera button and refresh the select dropdown.

        $('#select-project').val('').selectpicker('refresh');
        disableCameraButton();
        disableGalleryButton();

        $('#select-project').on('change', function () {
            let selected = $(this).val();
            if (selected && selected !== '') {
                enableCameraButton();
                enableGalleryButton();
            } else {
                // Disable the camera and gallery buttons if no selection
                disableCameraButton();
                disableGalleryButton();
            }
        });
    });

    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function (e) {
            if (input.id == 'photoGallery' && e.target.files.length > 1) {
                uploadMultiplePhotos(e.target.files);
            } else {
                const file = e.target.files[0];
                if (file) {
                    uploadPhoto(file, false);
                }
            }
        });
    });

    async function uploadMultiplePhotos(files) {
        const totalFiles = files.length;
        let successCount = 0;
        let failCount = 0;

        // Show initial progress
        alert(`Starting upload of ${totalFiles} photos...`);

        // Upload files one by one
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            try {
                let success = await uploadPhoto(file);
                if (success) {
                    successCount++;
                } else {
                    failCount++;
                }
            } catch (error) {
                failCount++;
                console.error(`Failed to upload ${file.name}:`, error);
            }
        }

        // Show final results
        if (failCount === 0) {
            alert(`All ${totalFiles} photos uploaded successfully!`);
        } else {
            alert(`Upload complete: ${successCount} successful, ${failCount} failed out of ${totalFiles} photos.`);
        }

    }

    async function uploadPhoto(file, uploadingMultiple) {
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
                if (!uploadingMultiple) {
                    alert(`Upload failed: ${response.status} - ${errorText}`);
                }
                return false;
            } else {
                let data = await response.json();
                if (!uploadingMultiple) {
                    alert(`Upload successful`);
                }
                return true;
            }
        } catch (error) {
            if (!uploadingMultiple) {
                alert(`Upload failed`);
            }
            return false;
        }
    }
</script>
</body>
</html>
