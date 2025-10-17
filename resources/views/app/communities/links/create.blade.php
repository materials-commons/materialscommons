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

                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" value="{{old('name')}}" type="text"
                           placeholder="Name..." required>
                </div>

                <div class="mb-3">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" name="summary" type="text" value="{{old('summary')}}"
                           placeholder="Summary..." required>
                </div>

                <div class="mb-3">
                    <label for="url">Url</label>
                    <input class="form-control" id="url" name="url" value="{{old('url')}}" type="url"
                           placeholder="Url..." required>
                </div>

                <div class="float-end">
                    <a href="{{route('communities.show', [$community])}}" class="action-link danger me-3">
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
