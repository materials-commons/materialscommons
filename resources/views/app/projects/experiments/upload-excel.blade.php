@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <div class="form-group">
        <label for="directories">Select Directory For Excel File</label>
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
        $('#dir-picker').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            let r = route('projects.files.upload', {
                project: project,
                file: e.target.value,
            }).url();

            if (uppy !== null) {
                uppy.close();
            }

            uppy = Uppy({
                restrictions: {
                    allowedFileTypes: ['.xlsx']
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
                setTimeout(() => window.location.replace("{{route('projects.experiments.create', [$project])}}"), 1000);
            });
        });
    </script>
@endpush