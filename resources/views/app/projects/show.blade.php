@extends('layouts.project')

@section('pageTitle', 'View Project')

@section('content')
    @component('components.card')
        @slot('header')
            Project: {{$project->name}}
            <a class="float-right action-link"
               href="{{route('projects.edit', $project->id)}}">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a data-toggle="modal" class="float-right action-link mr-4"
               href="#project-delete-{{$project->id}}">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>
            @component('app.projects.delete-project', ['project' => $project])
            @endcomponent
        @endslot

        @slot('body')
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$project->name}}</dd>
                    <dt class="col-sm-2">Description</dt>
                    <dd class="col-sm-10">{{$project->description}}</dd>
                    <dt class="col-sm-2">Owner</dt>
                    <dd class="col-sm-10">{{$project->owner->name}}</dd>
                    <dt class="col-sm-2">Last Updated</dt>
                    <dd class="col-sm-10">{{$project->updated_at->diffForHumans()}}</dd>
                </dl>
                <div class="form-group form-check-inline">
                    <input type="checkbox" class="form-check-input" id="default_project"
                           {{$project->default_project ? 'checked' : ''}} readonly onclick="return false;">
                    <label class="form-check-label" for="default_project">Default Project?</label>
                </div>
                <div class="form-group form-check-inline">
                    <input type="checkbox" class="form-check-input" id="is_active"
                           {{$project->is_active ? 'checked' : ''}} readonly onclick="return false;">
                    <label class="form-check-label" for="is_active">Is Active?</label>
                </div>
            </div>
        @endslot
    @endcomponent

    <br>

    @component('components.card')
        @slot('header')
            Experiments
            <a class="float-right action-link" href="">
                <i class="fas fa-plus mr-2"></i>Add
            </a>
        @endslot

        @slot('body')

            @if (count($project->experiments) == 0)
                <h4>No experiments</h4>
            @else
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
                            <td>{{$experiment->name}}</td>
                            <td>{{$experiment->description}}</td>
                            <td>{{$experiment->updated_at->diffForHumans()}}</td>
                            <td class="fs-11">
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
            @endif
        @endslot
    @endcomponent
@endsection