@extends('layouts.app')

@section('pageTitle', 'Upload Files To Community')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    <h3>Upload files for community: {{$community->name}}</h3>
    <br>
    <div class="row">
        <div class="col-8">
            <div id="file-upload" style="margin-top: 10px"></div>
        </div>
    </div>
@stop

@push('scripts')
    <script>
        let csrf = "{{csrf_token()}}";
        let r = "{{route('communities.files.upload.store', [$community])}}";
        let communityEditUrl = "{{route('communities.files.edit', [$community])}}";

        const uppy = new Uppy({
            restrictions: {
                maxFileSize: 750 * 1024 * 1024,
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

        uppy.on('complete', () => {
            setTimeout(() => window.location.replace(communityEditUrl), 1000);
        });
    </script>
@endpush
