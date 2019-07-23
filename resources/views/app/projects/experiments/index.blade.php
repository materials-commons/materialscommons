@extends('layouts.project')

@section('pageTitle', 'Experiments')

@section('content')
    @component('components.card')
        @slot('header')
            {{$project->name}} Experiments
            <a class="action-link float-right" href="{{route('projects.experiments.create', ['project' => $project->id])}}">
                <i class="fas fa-plus mr-2"></i>Create Experiment
            </a>
        @endslot

        @slot('body')
            <table class="table">
                <thead>
                <tr>
                    <th>Experiment</th>
                    <th>Description</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->experiments as $experiment)
                    <tr>
                        <td>
                            <a href="{{route('projects.experiments.show', ['project' => $project->id, 'experiment' => $experiment->id])}}">{{$experiment->name}}</a>
                        </td>
                        <td>{{$experiment->description}}</td>
                        <td>{{$experiment->updated_at->diffForHumans()}}</td>
                        <td>
                            <a href="{{route('projects.experiments.show', ['project' => $project->id, 'experiment' => $experiment->id])}}"
                               class="action-link">
                                <i class="fas fa-fw fa-eye"></i>
                            </a>
                            <a href="{{route('projects.experiments.edit', ['project' => $project->id, 'experiment' => $experiment->id])}}"
                               class="action-link">
                                <i class="fas fa-fw fa-edit"></i>
                            </a>
                            <a data-toggle="modal" href="#experiment-delete-{{$experiment->id}}" class="action-link">
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