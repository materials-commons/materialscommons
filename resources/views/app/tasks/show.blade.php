@extends('layouts.app')

@section('pageTitle', 'Tasks')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Task: {{$task->name}}
            <a class="float-end action-link"
               href="{{route('tasks.edit', $task->id)}}">
                <i class="fas fa-edit me-2"></i>Edit
            </a>

            <a data-toggle="modal" class="float-end action-link me-4"
               href="#item-delete-{{$task->id}}">
                <i class="fas fa-trash-alt me-2"></i>Delete
            </a>
            @component('components.item-delete', ['item' => $task, 'itemType' => 'Task', 'deleteRoute' => 'tasks.destroy'])
            @endcomponent
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@endsection
