@extends('layouts.app')

@section('pageTitle', "{$project->name} - Files")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <p class="col-10">
        Maximum file size is 250M. If you need to upload larger files please use
        <a href="{{route('projects.globus.uploads.index', [$project])}}">Globus Upload</a>.
    </p>
    <p>
        Please select a directory to upload your excel files to. Once you have selected a directory you will
        be able to upload files. You may change directories at any time.
    </p>
    <div class="form-group">
        <label for="directories">Select Directory You Are Uploading To</label>
        <select name="directory" class="selectpicker col-lg-8" id="dir-picker"
                data-style="btn-light no-tt"
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
        // $(document).ready(() => {
        let csrf = "{{csrf_token()}}";
        let project = "{{$project->id}}";
        let uppy = null;
        $('#dir-picker').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            let r = route('projects.files.upload', {
                project: project,
                file: e.target.value,
            });

            if (uppy !== null) {
                uppy.close();
            }

            uppy = new Uppy({
                restrictions: {
                    allowedFileTypes: ['.xlsx'],
                    maxFileSize: 250 * 1024 * 1024
                },
                onBeforeFileAdded: (currentFile, files) => {
                    if (currentFile.data.webkitRelativePath === "") {
                        return currentFile;
                    }

                    const modifiedFile = {
                        ...currentFile,
                    };

                    modifiedFile.meta.name = currentFile.data.webkitRelativePath;

                    return modifiedFile;
                }
            }).use(UppyDashboard, {
                trigger: '#file-upload',
                inline: true,
                showProgressDetails: true,
                proudlyDisplayPoweredByUppy: false,
            }).use(UppyXHRUpload, {endpoint: r, formData: true, limit: 1});

            uppy.on('file-added', () => {
                uppy.setMeta({_token: csrf});
                if (f.data.webkitRelativePath === "") {
                    uppy.setFileMeta(f.id, {"relativePath": ""});
                } else {
                    uppy.setFileMeta(f.id, {"relativePath": f.data.webkitRelativePath});
                }
            });

            uppy.on('complete', (result) => {
                setTimeout(() => window.location.replace("{{route('projects.experiments.create', [$project])}}"), 1000);
            });
        });
        // });
    </script>
@endpush
