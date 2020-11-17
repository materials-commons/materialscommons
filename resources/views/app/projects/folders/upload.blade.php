@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <div class="row justify-content-center">
        <p class="col-10">
            Maximum file size is 70M. If you need to upload larger files please use
            <a href="{{route('projects.globus.uploads.index', [$project])}}">Globus Upload</a>.
        </p>
        <div class="col-10">
            <h4>Adding files to directory {{$directory->path}}</h4>
        </div>
    </div>

    <div id="file-upload"></div>
@endsection

@push('scripts')
    <script>
        let csrf = "{{csrf_token()}}";
        let r = route('projects.files.upload', {
            project: "{{$project->id}}",
            file: "{{$directory->id}}"
        });
        let folderUrl = route('projects.folders.show', {
            project: "{{$project->id}}",
            folder: "{{$directory->id}}",
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

        uppy.on('complete', (result) => {
            setTimeout(() => window.location.replace(folderUrl), 1000);
        });
    </script>
@endpush
