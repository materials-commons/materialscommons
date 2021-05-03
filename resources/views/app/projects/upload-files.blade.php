@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <p>
        Maximum file size is 250M. If you need to upload larger files please use
        <a href="{{route('projects.globus.uploads.index', [$project])}}">Globus Upload</a>.
    </p>
    <p>
        Please select a directory to upload your files to. Once you have selected a directory you will
        be able to upload files. You may change directories at any time.
    </p>

    {{--    <div class="col-6">--}}
    <a class="btn btn-primary" style="margin-bottom: 8px"
       href="{{route('projects.folders.index', [$project])}}">
        Done Uploading Files
    </a>
    {{--    </div>--}}

    <div class="form-group">
        <label for="directories">Select Directory You Are Uploading To</label>
        <select name="directory" class="selectpicker col-lg-8" id="dir-picker"
                title="directories" data-live-search="true">
            @foreach($folders as $dir)
                <option data-token="{{$dir->id}}" value="{{$dir->id}}">
                    {{$dir->path}}
                </option>
            @endforeach
        </select>
    </div>

    <div id="file-upload"></div>
@endsection

@push('scripts')
    <script>
        let csrf = "{{csrf_token()}}";
        let project = "{{$project->id}}";
        let uppy = null;
        $('#dir-picker').on('changed.bs.select', (e) => {
            let r = route('projects.files.upload', {
                project: project,
                file: e.target.value,
            });

            if (uppy !== null) {
                uppy.close();
            }

            uppy = Uppy({
                restrictions: {
                    maxFileSize: 250 * 1024 * 1024
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

            {{--uppy.on('complete', (result) => {--}}
            {{--    setTimeout(() => window.location.replace("{{route('projects.experiments.create', [$project])}}"), 1000);--}}
            {{--});--}}
        });
    </script>
@endpush
