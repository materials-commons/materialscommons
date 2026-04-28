@extends('layouts.app')

@section('pageTitle', 'Edit Community File')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <h3 class="text-center">Delete Link {{$link->name}} In Community {{$community->name}}</h3>
    <br/>

    <form method="post" action="{{route('communities.links.destroy', [$community, $link])}}" id="delete-link">
        @csrf
        @method('delete')

        <div class="mb-3">
            <label for="name">Name</label>
            <input class="form-control" id="name" name="name" value="{{$link->name}}" type="text"
                   placeholder="Name..." readonly>
        </div>

        <div class="mb-3">
            <label for="summary">Summary</label>
            <input class="form-control" id="summary" name="summary" type="text"
                   value="{{$link->summary}}" placeholder="Summary..." readonly>
        </div>

        <div class="mb-3">
            <label for="url">Url</label>
            <input class="form-control" id="url" name="url" value="{{$link->url}}" type="url"
                   placeholder="Url..." readonly>
        </div>

        <div class="float-end">
            <a href="{{route('communities.links.edit', [$community])}}" class="action-link danger me-3">
                Cancel
            </a>
            <a class="action-link" href="#" onclick="document.getElementById('delete-link').submit()">
                Delete
            </a>
        </div>
    </form>
@stop
