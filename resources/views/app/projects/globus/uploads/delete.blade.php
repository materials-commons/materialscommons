@extends('layouts.app')

@section('pageTitle', "{$project->name} - Delete Globus Upload")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')

    <h3 class="text-center">Delete Globus Upload for project {{$project->name}}</h3>

    <form method="post" action="{{route('projects.globus.uploads.destroy', [$project, $globusUpload])}}"
          id="delete-upload">
        @csrf
        @method('delete')

        <div class="mb-3">
            <label for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{$globusUpload->name}}"
                   placeholder="Name..." readonly>
        </div>
        {{--                <div class="mb-3">--}}
        {{--                    <label for="description">Description</label>--}}
        {{--                    <textarea class="form-control" id="description" name="description" type="text"--}}
        {{--                              placeholder="Description..." readonly>{{$globusUpload->description}}</textarea>--}}
        {{--                </div>--}}
        <div class="mb-3">
            <a href="{{$globusUpload->globus_url}}" target="_blank" class="me-3">Goto Globus</a>
            <a href="https://app.globus.org/activity" target="_blank">View Globus Upload Activity</a>
        </div>
        <div class="mb-3">
            <p class="h5">
                Deleting the upload will prevent Globus from uploading any more files for this request. Any
                files that were uploaded will not be loaded into the project and instead will be deleted.
                You can check your uploads on globus
                by clicking on the "View Globus Upload Activity" link above.
            </p>
        </div>
        <div class="float-end">
            <a href="{{route('projects.globus.uploads.index', [$project])}}" class="action-link danger me-3">Cancel</a>
            <a href="#" class="action-link"
               onclick="document.getElementById('delete-upload').submit()">
                Delete
            </a>
        </div>
    </form>
@endsection
