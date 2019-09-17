@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.project')
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
            setTimeout(() => window.history.go(-1), 1000);
        });
    </script>
@endpush