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
        const uppy = new Uppy({
            restrictions: {
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
            fileManagerSelectionType: "both"
        }).use(UppyXHRUpload, {endpoint: r, formData: true});

        uppy.on('file-added', (f) => {
            uppy.setMeta({_token: csrf});
            if (f.data.webkitRelativePath === "") {
                uppy.setFileMeta(f.id, {"relativePath": ""});
            } else {
                uppy.setFileMeta(f.id, {"relativePath": f.data.webkitRelativePath});
            }
        });

        uppy.on('complete', () => {
            setTimeout(() => window.location.replace(folderUrl), 1000);
        });
    </script>
@endpush
