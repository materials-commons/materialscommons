@extends('layouts.app')

@section('pageTitle', 'Edit Community')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Edit Community
        @endslot

        @slot('body')
            <form method="post" action="{{route('communities.update', [$community])}}" id="community-update">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" value="{{old('name', $community->name)}}"
                           type="text"
                           placeholder="Name..." required>
                </div>

                <div class="mb-3">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" value="{{old('summary', $community->summary)}}"
                           name="summary">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..."
                              required>{{old('description', $community->description)}}</textarea>
                </div>
                <div class="float-end">
                    <a href="{{route('communities.index')}}" class="action-link danger me-3">
                        Cancel
                    </a>
                    <a class="action-link" href="#"
                       onclick="document.getElementById('community-update').submit()">
                        Update
                    </a>
                </div>

                <div class="mb-3 form-check-inline">
                    <label class="form-check-label me-2" for="public">Public?</label>
                    <input type="hidden" name="public" value="0"/>
                    <input type="checkbox" class="form-check-input" id="public"
                           value="1" name="public" {{old('public', $community->public) ? 'checked' : ''}}>
                </div>
            </form>

            <br>
            <br>
            <div class="row justify-content-center">
                <div class="col-11">
                    @include('app.communities._edit-community-help')
                </div>
            </div>

            <br>
            <br>
            @include('app.communities.ce-tabs.tabs')
            <br>

            @if(Request::routeIs('communities.edit'))
                @include('app.communities.ce-tabs.datasets')
            @elseif (Request::routeIs('communities.files.edit'))
                @include('app.communities.ce-tabs.files')
            @elseif(Request::routeIs('communities.links.edit'))
                @include('app.communities.ce-tabs.links')
            @endif

        @endslot
    @endcomponent

    @include('common.errors')

@stop
