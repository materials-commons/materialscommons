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
</head>

<body style="background-color: #ffffff">
<div class="container-fluid">
    <div class="row">
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="padding-top: 10px">

            <div class="mt-3">
                <h4 class="text-center mb-3">Materials Commons</h4>
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
                                <i class="fas fa-camera me-2"></i>Take Picture
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
                                <i class="fas fa-images me-2"></i>Choose from Gallery
                            </a>
                        </div>

                        <div class="mb-3 w-100" id="upload-progress-container" style="display: none;">
                            <div class="card border-info">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted" id="upload-status">Preparing upload...</small>
                                        <small class="text-muted" id="upload-counter">0/0</small>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                             role="progressbar" id="upload-progress-bar"
                                             style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-success" id="upload-success">0 successful</small>
                                        <small class="text-danger" id="upload-failed">0 failed</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 w-100">
                            <hr class="my-2">
                            <div class="text-center">
                                <small class="text-muted d-block mb-2">Questions? Check out our documentation</small>
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="/mcdocs2/" class="btn btn-link btn-sm text-info p-0 me-3">
                                        <i class="fas fa-book-open me-1"></i>
                                        Documentation
                                    </a>
                                    <span class="text-muted">•</span>
                                    <a href="{{route('logout-get')}}" class="btn btn-link btn-sm text-muted p-0 ms-3">
                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        Sign out
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-4 w-100">
                            <div class="card border-light" style="background-color: #f8f9fa;">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-camera text-muted" style="font-size: 2rem;"></i>
                                        <i class="fas fa-images text-muted ms-2" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6 class="card-title text-muted mb-3">Photo Upload Features</h6>
                                    <p class="card-text text-muted mb-3">
                                        Take photos or choose multiple images from your gallery to
                                        upload to your projects.
                                    </p>
                                    <div class="alert alert-info mb-0" role="alert" style="padding: 0.75rem 0.75rem;">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Login required to access photo upload features
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 w-100">
                            <a class="btn btn-success w-100 text-center" href="{{route('login')}}">
                                <i class="fas fa-user me-2"></i>Login To Upload Photos
                            </a>
                        </div>

                        <div class="mb-3 w-100">
                            <hr class="my-2">
                            <div class="text-center">
                                <small class="text-muted d-block mb-2">Questions? Check out our documentation</small>
                                <a href="/mcdocs2/" class="btn btn-link btn-sm text-info p-0">
                                    <i class="fas fa-book-open me-1"></i>
                                    Documentation
                                </a>
                            </div>
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

    function showProgressBar() {
        $('#upload-progress-container').show();
    }

    function hideProgressBar() {
        $('#upload-progress-container').hide();
        // Reset progress bar
        $('#upload-progress-bar').css('width', '0%').attr('aria-valuenow', 0);
        $('#upload-status').text('Preparing upload...');
        $('#upload-counter').text('0/0');
        $('#upload-success').text('0 successful');
        $('#upload-failed').text('0 failed');
    }

    function updateProgressBar(current, total, successCount, failCount, statusMessage) {
        const percentage = total > 0 ? Math.round((current / total) * 100) : 0;

        // Update progress bar
        $('#upload-progress-bar').css('width', percentage + '%').attr('aria-valuenow', percentage);

        // Update status text
        $('#upload-status').text(statusMessage);
        $('#upload-counter').text(`${current}/${total}`);
        $('#upload-success').text(`${successCount} successful`);
        $('#upload-failed').text(`${failCount} failed`);

        // Change progress bar color based on status
        const progressBar = $('#upload-progress-bar');
        progressBar.removeClass('bg-info bg-success bg-danger');

        if (current === total) {
            // Completed
            if (failCount === 0) {
                progressBar.addClass('bg-success');
            } else if (successCount === 0) {
                progressBar.addClass('bg-danger');
            } else {
                progressBar.addClass('bg-warning');
            }
            progressBar.removeClass('progress-bar-animated');
        } else {
            // In progress
            progressBar.addClass('bg-info progress-bar-animated');
        }
    }


    $(document).ready(() => {
        // Initially disable the camera and gallery buttons and refresh the select dropdown.

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
            if (input.id === 'photoGallery' && e.target.files.length > 1) {
                uploadMultiplePhotos(e.target.files);
            } else {
                const file = e.target.files[0];
                if (file) {
                    uploadSinglePhoto(file);
                }
            }
        });
    });

    async function uploadMultiplePhotos(files) {
        const totalFiles = files.length;
        let successCount = 0;
        let failCount = 0;

        // Show and initialize progress bar
        showProgressBar();
        updateProgressBar(0, totalFiles, successCount, failCount, 'Starting upload...');

        // Upload files one by one
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const currentFileNum = i + 1; // Show the file being uploaded started from 1, rather than 0 based counting.

            updateProgressBar(currentFileNum, totalFiles, successCount, failCount, `Uploading ${file.name}...`);

            try {
                let success = await uploadPhoto(file);
                if (success) {
                    successCount++;
                    updateProgressBar(currentFileNum, totalFiles, successCount, failCount, `Uploaded ${file.name}`);
                } else {
                    failCount++;
                    updateProgressBar(currentFileNum, totalFiles, successCount, failCount, `Failed to upload ${file.name}`);
                }
            } catch (error) {
                failCount++;
                console.error(`Failed to upload ${file.name}:`, error);
                updateProgressBar(currentFileNum, totalFiles, successCount, failCount, `Error uploading ${file.name}`);

            }

            // Add a small delay to show progress in the bar.
            await new Promise(resolve => setTimeout(resolve, 100));
        }

        // We've completed the upload, so show a final status message and leave the bar up
        // for a few seconds before hiding it.
        const finalMessage = failCount === 0 ? 'All photos uploaded successfully!' : 'Upload complete with some errors';
        updateProgressBar(totalFiles, totalFiles, successCount, failCount, finalMessage);

        setTimeout(() => hideProgressBar(), 2000);
    }

    async function uploadSinglePhoto(file) {
        showProgressBar();
        updateProgressBar(0, 1, 0, 0, `Uploading ${file.name}...`);
        let success = await uploadPhoto(file);
        if (success) {
            updateProgressBar(1, 1, 1, 0, 'Upload successful!');
        } else {
            updateProgressBar(1, 1, 0, 1, 'Upload failed');
        }

        setTimeout(() => hideProgressBar(), 2000);
    }

    // uploadPhoto is a function that takes a file and a boolean indicating whether it's being uploaded multiple times.
    // It shows a simpler progress if uploadingMultiple is false.
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
                await response.text();
                return false;
            } else {
                await response.text();
                return true;
            }
        } catch (error) {
            return false;
        }
    }
</script>
</body>
</html>
