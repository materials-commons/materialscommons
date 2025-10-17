@extends('layouts.app')

@section('pageTitle', 'Create Task')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Task
        @endslot

        @slot('body')
            <form method="post" action="{{route('tasks.store')}}" id="task-create">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="" placeholder="Name...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..."></textarea>
                </div>
                <div class="float-end">
                    <a href="{{route('tasks.index')}}" class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link" href="#" onclick="document.getElementById('task-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
