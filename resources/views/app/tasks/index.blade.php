@extends('layouts.app')

@section('pageTitle', 'Tasks')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Tasks
            <a class="action-link float-end"
               href="{{route('tasks.create')}}">
                <i class="fas fa-plus me-2"></i>Create Task
            </a>
        @endslot

        @slot('body')
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link no-underline {{$show == 'open' ? 'active' : ''}}"
                       href="{{route('tasks.index', ['show' => 'open'])}}">Open</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-underline {{$show == 'closed' ? 'active' : ''}}"
                       href="{{route('tasks.index', ['show' => 'closed'])}}">Closed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-underline {{$show == 'hold' ? 'active' : ''}}"
                       href="{{route('tasks.index', ['show' => 'hold'])}}">On Hold</a>
                </li>
            </ul>

            @if(count($tasks))
                <table class="table mt-4 table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Updated</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{$task->name}}</td>
                            <td>{{$task->updated_at->diffForHumans()}}</td>
                            <td>
                                <a href="{{route('tasks.show', [$task->id])}}"
                                   class="">
                                    <i class="fas fa-fw fa-eye"></i>
                                </a>
                                <a href="{{route('tasks.edit', [$task->id])}}"
                                   class="">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>
                                <a data-bs-toggle="modal" href="#item-delete-{{$task->id}}">
                                    <i class="fas fa-fw fa-trash-alt"></i>
                                </a>
                                @component('components.item-delete', ['item' => $task, 'itemType' => 'Task', 'deleteRoute' => 'tasks.destroy'])
                                @endcomponent
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="mt-3 ms-3">No Tasks</div>
            @endif
        @endslot
    @endcomponent
@endsection
