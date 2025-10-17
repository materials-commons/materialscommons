@extends('layouts.app')

@section('pageTitle', "{$project->name} - Delete Globus Download")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete Globus Download for project {{$project->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.globus.downloads.destroy', [$project, $globusDownload])}}"
                  id="delete-download">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$globusDownload->name}}"
                           placeholder="Name..." readonly>
                </div>
                {{--                <div class="form-group">--}}
                {{--                    <label for="description">Description</label>--}}
                {{--                    <textarea class="form-control" id="description" name="description" type="text"--}}
                {{--                              placeholder="Description..." readonly>{{$globusUpload->description}}</textarea>--}}
                {{--                </div>--}}
                <div class="form-group">
                    <a href="{{$globusDownload->globus_url}}" target="_blank" class="me-3">Goto Globus</a>
                    <a href="https://app.globus.org/activity" target="_blank">View Globus Upload Activity</a>
                </div>
                <div class="form-group">
                    <p class="h5">
                        Deleting the download will remove all the files that were setup for downloading. If your project
                        hasn't changed or you don't need to refresh the files, then you may want to keep this download.
                    </p>
                </div>
                <div class="float-end">
                    <a href="{{route('projects.globus.downloads.index', [$project])}}" class="action-link danger me-3">Cancel</a>
                    <a href="#" class="action-link"
                       onclick="document.getElementById('delete-download').submit()">
                        Delete
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
@endsection
