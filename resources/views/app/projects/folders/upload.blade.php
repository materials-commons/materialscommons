@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <div id="file-upload"></div>
@endsection

@push('scripts')
    <script>
        let csrf = "{{csrf_token()}}";
        let r = route('projects.files.upload', {
            project: "{{$project->id}}",
            file: "{{$directory->id}}"
        }).url();
        let folderUrl = route('projects.folders.show', {
            project: "{{$project->id}}",
            folder: "{{$directory->id}}",
        }).url();
        const uppy = Uppy()
            .use(UppyDashboard, {
                trigger: '#file-upload',
                inline: true,
                showProgressDetails: true,
                proudlyDisplayPoweredByUppy: false,
            })
            .use(UppyXHRUpload, {endpoint: r});

        uppy.on('file-added', () => {
            uppy.setMeta({_token: csrf});
        });

        uppy.on('complete', (result) => {
            setTimeout(() => window.location.replace(folderUrl), 1000);
        });
    </script>
@endpush
