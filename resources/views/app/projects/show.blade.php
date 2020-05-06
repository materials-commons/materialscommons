@extends('layouts.app')

@section('pageTitle', 'View Project')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Project: {{$project->name}}
            <a class="float-right action-link"
               href="{{route('projects.edit', $project->id)}}">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            @if ($project->owner_id == auth()->id())
                <a data-toggle="modal" class="float-right action-link mr-4"
                   href="#project-delete-{{$project->id}}">
                    <i class="fas fa-trash-alt mr-2"></i>Delete
                </a>
                @component('app.projects.delete-project', ['project' => $project])
                @endcomponent
            @endif
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $project])
                <a class="ml-4 action-link" href="{{route('projects.users.index', [$project])}}">
                    {{$project->users_count-1}} @choice("Member|Members", $project->users_count-1)
                </a>
            @endcomponent
        @endslot
    @endcomponent

    @component('components.card')
        @slot('header')
            Experiments
            <a class="float-right action-link" href="{{route('projects.experiments.create', ['project' => $project])}}">
                <i class="fas fa-plus mr-2"></i>Create Experiment
            </a>
        @endslot

        @slot('body')

            <table id="experiments" class="table table-hover" style="width:100%">
                <thead>
                <th>Name</th>
                <th>Summary</th>
                <th>Updated</th>
                <th></th>
                </thead>
                <tbody>
                @foreach($project->experiments as $experiment)
                    <tr>
                        <td>
                            <a href="{{route('projects.experiments.show', [$project, $experiment])}}">
                                {{$experiment->name}}
                            </a>
                        </td>
                        <td>{{$experiment->summary}}</td>
                        <td>{{$project->updated_at->diffForHumans()}}</td>
                        <td>
                            <div class="float-right">
                                <a href="{{route('projects.experiments.show', [$project, $experiment])}}"
                                   class="action-link">
                                    <i class="fas fa-fw fa-eye"></i>
                                </a>
                                <a href="" class="action-link">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>
                                @if(auth()->id() == $experiment->owner_id || auth()->id() == $project->owner_id)
                                    <a href="{{route('projects.experiments.delete', [$project, $experiment])}}"
                                       class="action-link">
                                        <i class="fas fa-fw fa-trash-alt"></i>
                                    </a>
                                @endif
                            </div>
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
@endsection
