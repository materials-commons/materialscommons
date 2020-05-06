@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Experiments for {{$project->name}}
            <a class="action-link float-right"
               href="{{route('projects.experiments.create', ['project' => $project->id])}}">
                <i class="fas fa-plus mr-2"></i>Create Experiment
            </a>
        @endslot

        @slot('body')
            <table id="experiments" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Experiment</th>
                    <th>Summary</th>
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
                        <td>{{$experiment->summary}}</td>
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
                            @if(auth()->id() == $experiment->owner_id || auth()->id() == $project->owner_id)
                                <a href="{{route('projects.experiments.delete', [$project, $experiment])}}"
                                   class="action-link">
                                    <i class="fas fa-fw fa-trash-alt"></i>
                                </a>
                            @endif
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
                $('#experiments').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
