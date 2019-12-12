@extends('layouts.app')

@section('pageTitle', 'Create Globus Upload')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Globus Upload for project {{$project->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.globus.uploads.mark_done', [$project, $globusUpload])}}"
                  id="complete-upload">
                @csrf

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
                    <a href="{{$globusUpload->globus_url}}" target="_blank">Goto Globus</a>
                </div>
                <div class="form-group">
                    <p>
                        Before marking as complete please make sure all globus transfers are done.
                    </p>
                </div>
                <div class="float-right">
                    <a href="{{route('projects.globus.status', [$project])}}" class="action-link danger mr-3">Cancel</a>
                    <a href="#" class="action-link"
                       onclick="document.getElementById('complete-upload').submit()">
                        Mark Done
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
@endsection
