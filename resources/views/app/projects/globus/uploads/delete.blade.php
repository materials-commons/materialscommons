@extends('layouts.app')

@section('pageTitle', 'Create Globus Upload')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete Globus Upload for project {{$project->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.globus.uploads.destroy', [$project, $globusUpload])}}"
                  id="delete-upload">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$globusUpload->name}}"
                           placeholder="Name..." readonly>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..." readonly>{{$globusUpload->description}}</textarea>
                </div>
                <div class="form-group">
                    <a href="{{$globusUpload->globus_url}}" target="_blank" class="mr-3">Goto Globus</a>
                    <a href="https://app.globus.org/activity" target="_blank">View Globus Upload Activity</a>
                </div>
                <div class="form-group">
                    <p class="h5">
                        Deleting the upload will prevent Globus from uploading any more files for this request. Any
                        files that were uploaded will not be loaded into the project and instead will be deleted.
                        You can check your uploads on globus
                        by clicking on the "View Globus Upload Activity" link above.
                    </p>
                </div>
                <div class="float-right">
                    <a href="{{route('projects.globus.status', [$project])}}" class="action-link danger mr-3">Cancel</a>
                    <a href="#" class="action-link"
                       onclick="document.getElementById('delete-upload').submit()">
                        Delete
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
@endsection