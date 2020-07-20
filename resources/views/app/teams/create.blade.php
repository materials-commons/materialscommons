@extends('layouts.app')

@section('pageTitle', 'Create Team')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Team
        @endslot

        @slot('body')
            <form method="post" action="{{route('teams.store')}}" id="team-create">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
                           placeholder="Name...">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description...">{{old('description')}}</textarea>
                </div>

                <div class="float-right">
                    <a href="{{route('teams.index')}}" class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link mr-3" href="#" onclick="document.getElementById('team-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
@stop
