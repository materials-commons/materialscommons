@extends('layouts.app')

@section('pageTitle', "{$project->name} - Files")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <div class="row justify-content-center">
        <h3 class="col-10">
            Maximum file size is 750M. If you need to upload larger files please use
            <a href="{{route('projects.globus.uploads.index', [$project])}}">Globus Upload</a>.
        </h3>
    </div>
    <br/>
    <div class="row justify-content-center">
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
            arg: "{{$arg}}",
            destdir: "{{$destDir}}",
            destproj: "{{$destProj}}",
        });
        const uppy = new Uppy({
            restrictions: {
                maxFileSize: 750 * 1024 * 1024
            },
            onBeforeFileAdded: (currentFile, files) => {
                // console.log('onBeforeFileAdded', currentFile);
                // if (currentFile.data.relativePath === "") {
                //     console.log('I am returning the currentFile');
                //     return currentFile;
                // }

                const modifiedFile = {
                    ...currentFile,
                };

                if (currentFile.data.relativePath == null) {
                    if (currentFile.data.webkitRelativePath != null && currentFile.data.webkitRelativePath !== "") {
                        modifiedFile.meta.name = currentFile.data.webkitRelativePath;
                        modifiedFile.meta.relativePath = currentFile.data.webkitRelativePath;
                    } else {
                        modifiedFile.meta.name = currentFile.data.name;
                        modifiedFile.meta.relativePath = "";
                    }
                } else {
                    modifiedFile.meta.name = currentFile.data.relativePath;
                }

                console.log('modifiedFile', modifiedFile);
                return modifiedFile;
            }
        }).use(UppyDashboard, {
            trigger: '#file-upload',
            inline: true,
            showProgressDetails: true,
            proudlyDisplayPoweredByUppy: false,
            fileManagerSelectionType: "both",
        }).use(UppyXHRUpload, {endpoint: r, formData: true, limit: 1});

        uppy.on('file-added', (f) => {
            uppy.setMeta({_token: csrf});
            console.log(f);
            let relativePath = "";
            if ('relativePath' in f.data) {
                console.log("relativePath exists:", f.data.relativePath);
                relativePath = f.data.relativePath;
                if (relativePath === null) {
                    relativePath = "";
                }
            } else if (f.data.webkitRelativePath === "") {
                uppy.setFileMeta(f.id, {"relativePath": ""});
            } else {
                console.log("setting relativePath to webkitRelativePath");
                uppy.setFileMeta(f.id, {"relativePath": f.data.webkitRelativePath});
            }

            console.log("relativePath: " + relativePath + "");
            uppy.setFileMeta(f.id, {"relativePath": relativePath});
            console.log('past setFileMeta');
            // if (f.data.webkitRelativePath === "") {
            //     uppy.setFileMeta(f.id, {"relativePath": ""});
            // } else {
            //     uppy.setFileMeta(f.id, {"relativePath": f.data.webkitRelativePath});
            // }
        });

        uppy.on('complete', () => {
            // setTimeout(() => window.location.replace(folderUrl), 1000);
        });
    </script>
@endpush
