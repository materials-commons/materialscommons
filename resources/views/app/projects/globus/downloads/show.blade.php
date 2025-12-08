@extends('layouts.app')

@section('pageTitle', "{$project->name} - Show Globus Download")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Globus Download for project {{$project->name}}</h3>
    <br/>

    <form>
        <div class="mb-3">
            <label for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{$globusDownload->name}}"
                   placeholder="Name..." readonly>
        </div>
        {{--                <div class="mb-3">--}}
        {{--                    <label for="description">Description</label>--}}
        {{--                    <textarea class="form-control" id="description" name="description" type="text"--}}
        {{--                              placeholder="Description..." readonly>{{$globusDownload->description}}</textarea>--}}
        {{--                </div>--}}
        <div class="mb-3">
            <a href="{{$globusDownload->globus_url}}" target="_blank">Goto Globus</a>
        </div>
        <div class="float-end">
            <a href="{{route('projects.globus.downloads.index', [$project])}}" class="btn btn-success">Done</a>
        </div>
    </form>
@endsection
