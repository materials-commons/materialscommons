@extends('layouts.app')

@section('pageTitle', 'Tasks')

@section('content')
    @component('components.card')
        @slot('header')
            Task: {{$task->name}}
            <a class="float-right action-link"
               href="{{route('tasks.edit', $task->id)}}">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a data-toggle="modal" class="float-right action-link mr-4"
               href="#item-delete-{{$task->id}}">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>
            @component('components.item-delete', ['item' => $task, 'itemType' => 'Task', 'deleteRoute' => 'tasks.destroy'])
            @endcomponent
        @endslot

        @slot('body')
        @endslot
    @endcomponent
@endsection