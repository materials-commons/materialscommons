@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    <div class="row mb-10">
        <p class="col-10">
            Maximum file size is 70M. If you need to upload larger files please use
            <a href="{{route('projects.globus.uploads.index', [$project])}}">Globus Upload</a>.
        </p>
        <div class="col-8">
            <a class="btn btn-primary float-right"
               href="{{route('public.publish.wizard.create_workflow', [$project])}}">
                Done Uploading Files
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <div id="file-upload" style="margin-top: 10px"></div>
        </div>
    </div>
@stop

@push('scripts')
    <script>
        let csrf = "{{csrf_token()}}";
        let r = route('projects.files.upload', {
            project: "{{$project->id}}",
            file: "{{$directory->id}}"
        });
        const uppy = Uppy({
            restrictions: {
                maxFileSize: 70 * 1024 * 1024
            }
        }).use(UppyDashboard, {
            trigger: '#file-upload',
            inline: true,
            showProgressDetails: true,
            proudlyDisplayPoweredByUppy: false,
        }).use(UppyXHRUpload, {endpoint: r});

        uppy.on('file-added', () => {
            uppy.setMeta({_token: csrf});
        });

        // uppy.on('complete', (result) => {
        //     setTimeout(() => window.location.replace(folderUrl), 1000);
        // });
    </script>
@endpush
