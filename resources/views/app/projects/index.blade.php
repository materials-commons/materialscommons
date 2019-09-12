@extends('layouts.app')

@section('pageTitle', 'Projects')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.index'))

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
            <table id="projects" class="table" style="width:100%">
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
                        <td>
                            <a href="{{route('projects.show', ['id' => $project->id])}}" class="">{{$project->name}}</a>
                        </td>
                        <td>{{$project->description}}</td>
                        <td>{{$project->updated_at->diffForHumans()}}</td>
                        <td>
                            <div class="float-right">
                                <a href="{{route('projects.show', ['id' => $project->id])}}" class="action-link">
                                    <i class="fas fa-fw fa-eye"></i>
                                </a>
                                <a href="{{route('projects.edit', ['id' => $project->id])}}" class="action-link">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>
                                <a data-toggle="modal" href="#project-delete-{{$project->id}}" class="action-link">
                                    <i class="fas fa-fw fa-trash-alt"></i>
                                </a>
                            </div>
                            @component('app.projects.delete-project', ['project' => $project])
                            @endcomponent
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#projects').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
