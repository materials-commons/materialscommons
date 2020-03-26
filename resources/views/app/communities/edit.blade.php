@extends('layouts.app')

@section('pageTitle', 'Edit Community')

@section('nav')
    @include('layouts.navs.app')
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
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" value="{{$community->name}}" type="text"
                           placeholder="Name..." required>
                </div>

                <div class="form-group">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" value="{{$community->summary}}" name="summary">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..." required>{{$community->description}}</textarea>
                </div>
                <div class="float-right">
                    <a href="{{route('communities.index')}}" class="action-link danger mr-3">
                        Cancel
                    </a>
                    <a class="action-link" href="#"
                       onclick="document.getElementById('community-update').submit()">
                        Update
                    </a>
                </div>

                <div class="form-group form-check-inline">
                    <label class="form-check-label mr-2" for="public">Public?</label>
                    <input type="hidden" name="public" value="0"/>
                    <input type="checkbox" class="form-check-input" id="public"
                           value="1" name="public" {{$community->public ? 'checked' : ''}}>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')

@stop