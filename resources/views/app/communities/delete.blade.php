@extends('layouts.app')

@section('pageTitle', 'Delete Community')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete Community {{$community->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('communities.destroy', [$community])}}" id="community-delete">
                @csrf
                @method('delete')
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" value="{{$community->name}}" type="text"
                           placeholder="Name..." readonly>
                </div>

                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..." readonly>{{$community->description}}</textarea>
                </div>
                <div class="float-end">
                    <a href="{{route('communities.index')}}" class="action-link danger me-3">
                        Cancel
                    </a>
                    <a class="action-link" href="#"
                       onclick="document.getElementById('community-delete').submit()">
                        Delete
                    </a>
                </div>

                <div class="mb-3 form-check-inline">
                    <label class="form-check-label me-2" for="public">Public?</label>
                    <input type="checkbox" class="form-check-input" id="public"
                           value="1" {{$community->public ? 'checked' : ''}} readonly>
                </div>
            </form>
        @endslot
    @endcomponent

@stop
