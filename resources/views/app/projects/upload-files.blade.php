@extends('layouts.app')

@section('pageTitle', "{$project->name} - Files")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <p>
        Maximum file size is 750M. If you need to upload larger files please use
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

    <div class="mb-3">
        <label for="directories">Select Directory You Are Uploading To</label>
        <select name="directory" class="" id="dir-picker" title="directories">
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
        $(document).ready(() => {
            let csrf = "{{csrf_token()}}";
            let project = "{{$project->id}}";
            let uppy = null;
            new TomSelect("#dir-picker", {
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                onChange: function (f) {
                    let r = route('projects.files.upload', {
                        project: project,
                        file: f,
                    });

                    if (uppy !== null) {
                        uppy.close();
                    }

                    uppy = new Uppy({
                        restrictions: {
                            maxFileSize: 750 * 1024 * 1024
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
                        fileManagerSelectionType: "both",
                    }).use(UppyXHRUpload, {endpoint: r, formData: true, limit: 1});

                    uppy.on('file-added', () => {
                        uppy.setMeta({_token: csrf});
                        if (f.data.webkitRelativePath === "") {
                            uppy.setFileMeta(f.id, {"relativePath": ""});
                        } else {
                            uppy.setFileMeta(f.id, {"relativePath": f.data.webkitRelativePath});
                        }
                    });
                }
            });
        });
    </script>
@endpush
