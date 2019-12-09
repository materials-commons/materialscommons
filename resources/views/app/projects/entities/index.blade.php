@extends('layouts.app')

@section('pageTitle', 'Samples')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.entities.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Samples
            <a class="action-link float-right" href="{{route('projects.entities-export', ['project' => $project])}}">
                <i class="fas fa-download mr-2"></i>Download As Excel
            </a>

            {{--            <a class="action-link float-right mr-4" href="#">--}}
            {{--                <i class="fas fa-plus mr-2"></i>Create Sample--}}
            {{--            </a>--}}
        @endslot

        @slot('body')
            <br>
            <table id="entities" class="table table-hover" width="100%">
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
                $('#entities').DataTable({
                    serverSide: true,
                    processing: true,
                    response: true,
                    stateSave: true,
                    ajax: "{{route('dt_get_project_entities', [$project->id])}}",
                    columns: [
                        {
                            name: 'name',
                            render: (data, type, row) => {
                                if (type !== 'display') {
                                    return data;
                                }
                                let r = route('projects.entities.show',
                                    {project: "{{$project->id}}", entity: row["1"]}).url();
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
