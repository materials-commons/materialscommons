@extends('layouts.app')

@section('pageTitle', 'Projects')

@section('content')
    @component('components.card')
        @slot('header')
            Projects
            <a class="action-link float-right"
               href="{{route('projects.create')}}">
                <i class="fas fa-plus mr-2"></i>Create Project
            </a>
        @endslot

        @slot('body')
            <table class="table">
                <thead>
                <tr>
                    <th>Project</th>
                    <th>Description</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{$project->name}}</td>
                        <td>{{$project->description}}</td>
                        <td>{{$project->updated_at->diffForHumans()}}</td>
                        <td>
                            <a href="{{route('projects.show', ['id' => $project->id])}}"
                               class="">
                                <i class="fas fa-fw fa-eye"></i>
                            </a>
                            <a href="{{route('projects.edit', ['id' => $project->id])}}"
                               class="">
                                <i class="fas fa-fw fa-edit"></i>
                            </a>
                            <a data-toggle="modal" href="#project-delete-{{$project->id}}">
                                <i class="fas fa-fw fa-trash-alt"></i>
                            </a>
                            @component('app.projects.delete-project', ['project' => $project])
                            @endcomponent
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@stop
