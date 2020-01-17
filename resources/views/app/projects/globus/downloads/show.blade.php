@extends('layouts.app')

@section('pageTitle', 'Show Globus Download')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Globus Download for project {{$project->name}}
        @endslot

        @slot('body')
            <form>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$globusDownload->name}}"
                           placeholder="Name..." readonly>
                </div>
                {{--                <div class="form-group">--}}
                {{--                    <label for="description">Description</label>--}}
                {{--                    <textarea class="form-control" id="description" name="description" type="text"--}}
                {{--                              placeholder="Description..." readonly>{{$globusDownload->description}}</textarea>--}}
                {{--                </div>--}}
                <div class="form-group">
                    <a href="{{$globusDownload->globus_url}}" target="_blank">Goto Globus</a>
                </div>
                <div class="float-right">
                    <a href="{{route('projects.globus.downloads.index', [$project])}}" class="btn btn-success">Done</a>
                </div>
            </form>
        @endslot
    @endcomponent
@endsection
