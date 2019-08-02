@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Experiments
            <a class="action-link float-right" href="{{route('experiments.create')}}">
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
                @foreach($experiments as $experiment)
                    <tr>
                        <td>
                            <a href="{{route('experiments.show', ['id' => $experiment->id])}}">{{$experiment->name}}</a>
                        </td>
                        <td>{{$experiment->description}}</td>
                        <td>{{$experiment->updated_at->diffForHumans()}}</td>
                        <td>
                            <a href="{{route('experiments.show', ['id' => $experiment->id])}}" class="action-link">
                                <i class="fas fa-fw fa-eye"></i>
                            </a>
                            <a href="{{route('experiments.edit', ['id' => $experiment->id])}}" class="action-link">
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
