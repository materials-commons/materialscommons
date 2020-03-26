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

            <a data-toggle="modal" class="float-right action-link mr-4"
               href="#project-delete-{{$project->id}}">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>
            @component('app.projects.delete-project', ['project' => $project])
            @endcomponent
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
                <th>ID</th>
                <th>Summary</th>
                <th>Updated</th>
                <th></th>
                </thead>
            </table>


        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                let projectId = "{{$project->id}}";
                $('#experiments').DataTable({
                    serverSide: true,
                    processing: true,
                    response: true,
                    stateSave: true,
                    ajax: "{{route('get_project_experiments', [$project->id])}}",
                    columns: [
                        {
                            name: 'name',
                            render: (data, type, row) => {
                                if (type === 'display') {
                                    let r = route('projects.experiments.show', {
                                        project: "{{$project->id}}",
                                        experiment: row["1"]
                                    }).url();
                                    return `<a href="` + r + `">` + data + `</a>`;
                                }
                                return data;
                            }
                        },
                        {name: 'id'},
                        {name: 'summary'},
                        {
                            name: 'updated_at',
                            render: (data, type, row) => {
                                if (type === 'display') {
                                    return moment(data + "+0000", "YYYY-MM-DD HH:mm:ss Z").fromNow();
                                }
                                return data;
                            }
                        },
                        {
                            name: 'action',
                            data: null,
                            render: (data, type, row) => {
                                let showRoute = route('projects.experiments.show', {
                                    project: projectId,
                                    experiment: row["1"]
                                }).url();
                                let editRoute = route('projects.experiments.edit', {
                                    project: projectId,
                                    experiment: row["1"]
                                }).url();
                                let deleteRoute = route('projects.experiments.delete', {
                                    project: projectId,
                                    experiment: row["1"]
                                }).url();
                                return `
                                   <a href="${showRoute}" class="action-link"><i class="fas fa-fw fa-eye"></i></a>
                                   <a href="${editRoute}" class="action-link"><i class="fas fa-fw fa-edit"></i></a>
                                   <a href="${deleteRoute}" class="action-link"><i class="fas fa-fw fa-trash-alt"></i></a>`;
                            }
                        }
                    ],
                    columnDefs: [
                        {
                            targets: [1],
                            visible: false,
                        }
                    ]
                });
            });
        </script>
    @endpush
@endsection
