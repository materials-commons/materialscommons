@extends('layouts.app')

@section('pageTitle', 'Processes')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.activities.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Processes
            <a class="action-link float-right" href="#">
                <i class="fas fa-plus mr-2"></i>Create Process
            </a>
        @endslot

        @slot('body')
            <br>
            <table id="activities" class="table table-hover" width="100%">
                <thead>
                <th>Name</th>
                <th>ID</th>
                <th>Description</th>
                <th>Updated</th>
                </thead>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                let projectId = "{{$project->id}}";
                $('#activities').DataTable({
                    serverSide: true,
                    processing: true,
                    response: true,
                    stateSave: true,
                    ajax: "{{route('dt_get_project_activities', [$project->id])}}",
                    columns: [
                        {
                            name: 'name',
                            render: (data, type, row) => {
                                if (type !== 'display') {
                                    return data;
                                }
                                let r = route("projects.activities.show", {
                                    project: "{{$project->id}}",
                                    activity: row["1"]
                                }).url();
                                return `<a href="${r}">${data}</a>`;
                            }
                        },
                        {name: 'id'},
                        {name: 'description'},
                        {
                            name: 'updated_at',
                            render: (data, type, row) => {
                                if (type !== 'display') {
                                    return data;
                                }
                                return moment(data + "+0000", "YYYY-MM-DD HH:mm:ss Z").fromNow();
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
@stop
