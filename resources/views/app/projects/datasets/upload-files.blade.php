@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.app.project')
@stop
@section('content')
    <div class="row mb-10">
        <div class="col-8">
            <a class="btn btn-primary float-right"
               href="{{route('projects.datasets.create-data', [$project, $dataset, $directory])}}">
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
    </script>
@endpush