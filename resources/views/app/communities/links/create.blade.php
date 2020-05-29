@extends('layouts.app')

@section('pageTitle', 'Create Community Link')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Link In Community {{$community->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('communities.links.store', [$community])}}" id="create-link">
                @csrf

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" value="{{old('name')}}" type="text"
                           placeholder="Name..." required>
                </div>

                <div class="form-group">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" name="summary" type="text" value="{{old('summary')}}"
                           placeholder="Summary...">
                </div>

                <div class="form-group">
                    <label for="name">Url</label>
                    <input class="form-control" id="url" name="url" value="{{old('url')}}" type="url"
                           placeholder="Url..." required>
                </div>

                <div class="float-right">
                    <a href="{{route('communities.show', [$community])}}" class="action-link danger mr-3">
                        Cancel
                    </a>
                    <a class="action-link" href="#"
                       onclick="document.getElementById('create-link').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')

@stop