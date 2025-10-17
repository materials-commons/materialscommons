@extends('layouts.app')

@section('pageTitle', 'Edit Community File')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Edit Link {{$link->name}} In Community {{$community->name}}
        @endslot
        @slot('body')
            <form method="post" action="{{route('communities.links.update-link', [$community, $link])}}"
                  id="update-link">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" value="{{old('name', $link->name)}}" type="text"
                           placeholder="Name..." required>
                </div>

                <div class="mb-3">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" name="summary" type="text"
                           value="{{old('summary', $link->summary)}}" placeholder="Summary..." required>
                </div>

                <div class="mb-3">
                    <label for="url">Url</label>
                    <input class="form-control" id="url" name="url" value="{{old('url', $link->url)}}" type="url"
                           placeholder="Url..." required>
                </div>

                <div class="float-end">
                    <a href="{{route('communities.show', [$community])}}" class="action-link danger me-3">
                        Cancel
                    </a>
                    <a class="action-link" href="#" onclick="document.getElementById('update-link').submit()">
                        Update
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
@stop
