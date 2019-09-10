@extends('layouts.app')

@section('pageTitle', 'Samples')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Samples
            <a class="action-link float-right" href="#">
                <i class="fas fa-plus mr-2"></i>Create Sample
            </a>
        @endslot

        @slot('body')
            <br>
            <table id="entities" class="table" width="100%">
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

                                return `<a href="#">${data}</a>`;
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
