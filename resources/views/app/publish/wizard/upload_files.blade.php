@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    <div class="row mb-10">
        <p class="col-10">
            Maximum file size is 750M. If you need to upload larger files please use
            <a href="{{route('projects.globus.uploads.index', [$project])}}">Globus Upload</a>.
        </p>
        <div class="col-8">
            <a class="btn btn-primary float-end"
               href="{{route('public.publish.wizard.create_workflow', [$project])}}">
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
        });
        const uppy = new Uppy({
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

        // uppy.on('complete', (result) => {
        //     setTimeout(() => window.location.replace(folderUrl), 1000);
        // });
    </script>
@endpush
