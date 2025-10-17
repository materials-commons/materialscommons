@extends('layouts.app')

@section('pageTitle', "{$project->name} - Show Globus Upload")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Globus Upload for project {{$project->name}}
        @endslot

        @slot('body')
            <form>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$globusUpload->name}}"
                           placeholder="Name..." readonly>
                </div>
                {{--                <div class="form-group">--}}
                {{--                    <label for="description">Description</label>--}}
                {{--                    <textarea class="form-control" id="description" name="description" type="text"--}}
                {{--                              placeholder="Description..." readonly>{{$globusUpload->description}}</textarea>--}}
                {{--                </div>--}}
                <div class="form-group">
                    <a href="{{$globusUpload->globus_url}}" target="_blank">Goto Globus</a>
                </div>
                <div class="float-end">
                    <a href="{{route('projects.globus.uploads.index', [$project])}}" class="btn btn-success">Done</a>
                </div>
            </form>
        @endslot
    @endcomponent
@endsection
