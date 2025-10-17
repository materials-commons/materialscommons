@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Globus Upload")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Mark Globus upload done for {{$project->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.globus.uploads.mark_done', [$project, $globusUpload])}}"
                  id="complete-upload">
                @csrf

                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$globusUpload->name}}"
                           placeholder="Name..." readonly>
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..." readonly>{{$globusUpload->description}}</textarea>
                </div>
                <div class="mb-3">
                    <a href="{{$globusUpload->globus_url}}" target="_blank" class="me-3">Goto Globus</a>
                    <a href="https://app.globus.org/activity" target="_blank">View Globus Upload Activity</a>
                </div>
                <div class="mb-3">
                    <p class="h5">
                        Before marking as complete please make sure all globus transfers are done. You can check your
                        uploads on globus
                        by clicking on the "View Globus Upload Activity" link above.
                    </p>
                </div>
                <div class="float-end">
                    <a href="{{route('projects.globus.uploads.index', [$project])}}" class="action-link danger me-3">Cancel</a>
                    <button class="btn btn-success">Mark Done</button>
                </div>
            </form>
        @endslot
    @endcomponent
@endsection
