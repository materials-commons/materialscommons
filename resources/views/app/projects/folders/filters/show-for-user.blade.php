@extends('layouts.app')

@section('pageTitle', 'Filter By User')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <table id="files" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Path</th>
            <th>ID</th>
            <th>Type</th>
            <th>Real Size</th>
            <th>Size</th>
            <th>Created</th>
            <th></th>
        </tr>
        </thead>
    </table>
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#files').DataTable({
                serverSide: true,
                processing: true,
                response: true,
                stateSave: true,
                ajax: "{{route('projects.folders.filter.dt_get_files_for_user_filter', [$project, $user])}}",
                columns: [
                    {name: 'name'},
                    {name: 'directory.path', orderable: false},
                    {name: 'id'},
                    {name: 'mime_type'},
                    {
                        name: 'size',
                        render: function (data, type, row) {
                            if (type === 'display') {
                                return formatters.humanFileSize(data);
                            }
                            return data;
                        }
                    },
                    {name: 'size'},
                    {name: 'created_at'}
                ],
                columnDefs: [
                    {targets: [2], visible: false, searchable: false},
                    {orderData: [5], targets: [4]},
                    {targets: [5], visible: false},
                    // {targets: [6], visible: false},
                    // {orderData: [4], targets: [3]},
                    // {targets: [4], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush