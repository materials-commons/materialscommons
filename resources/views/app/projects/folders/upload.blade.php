@extends('layouts.app')

@section('pageTitle', "{$project->name} - Files")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <div class="row justify-content-center">
        <p class="col-10">
            Maximum file size is 250M. If you need to upload larger files please use
            <a href="{{route('projects.globus.uploads.index', [$project])}}">Globus Upload</a>.
        </p>
        <div class="col-10">
            <h4>Adding files to directory {{$directory->path}}</h4>
        </div>
    </div>

    <form method="post" action="">
        @csrf
        <div class="form-group">
            <label for="sample">Sample</label>
            <input class="form-control" id="sample" name="sample" value="" type="text"
                   placeholder="Sample..." required>
        </div>

        <div class="form-group">
            <label for="experiments">Experiments</label>
            <select name="select-experiment" class="selectpicker col-lg-8" id="select-experiment"
                    title="experiments"
                    data-live-search="true">
                @foreach($project->experiments as $experiment)
                    <option data-token="{{$experiment->id}}" value="{{$experiment->id}}">
                        {{$experiment->name}}
                    </option>
                @endforeach
            </select>
        </div>

    </form>

    <div id="file-upload"></div>
@endsection

@push('scripts')
    <script>
        let sample = "";
        let experimentId = "";

        // $('#select-experiment').on('changed.bs.select', function (e) {
        //     console.log("changed.bs.select called = ", e.target.value);
        //     experimentId = e.target.value;
        // });

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
                maxFileSize: 250 * 1024 * 1024
            }
        }).use(UppyDashboard, {
            trigger: '#file-upload',
            inline: true,
            showProgressDetails: true,
            proudlyDisplayPoweredByUppy: false,
        }).use(UppyXHRUpload, {endpoint: r});

        uppy.on('file-added', () => {
            uppy.setMeta({_token: csrf, sample: sample, experiment: experimentId});
            sample = document.getElementById("sample").value;
            experimentId = $("select[name=select-experiment]").val();
            console.log("sample = ", sample);
            console.log("experimentId = ", experimentId);
            uppy.setMeta({_token: csrf, sample: sample, experiment: experimentId});
        });

        // uppy.on('complete', (result) => {
        //     setTimeout(() => window.location.replace(folderUrl), 1000);
        // });
    </script>
@endpush
