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

        const uppy = Uppy({
            restrictions: {
                maxFileSize: 250 * 1024 * 1024,
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

        uppy.on('complete', () => {
            setTimeout(() => window.location.replace(communityEditUrl), 1000);
        });
    </script>
@endpush
