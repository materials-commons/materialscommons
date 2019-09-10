@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            {{$directory->name}}
        @endslot

        @slot('body')
            <table id="files" class="table" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Size</th>
                </tr>
                </thead>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#files').DataTable({
                    serverSide: true,
                    processing: true,
                    response: true,
                    stateSave: true,
                    ajax: "{{route('projects.get_root_folder', [$project->id])}}",
                    columns: [
                        {
                            name: 'name',
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    let rowType = row["2"];
                                    let objectId = row["1"];
                                    let r, icon;
                                    if (rowType === 'directory') {
                                        // rowType === 'directory'
                                        r = route('projects.folders.show', [{{$project->id}}, objectId]).url();
                                        icon = `<i class="fa-fw fas mr-2 ` + `fa-folder"></i>`
                                    } else {
                                        // else type === some media type for a file
                                        r = route('projects.files.show', [{{$project->id}}, objectId]).url();
                                        icon = `<i class="fa-fw fas mr-2 ` + `fa-file"></i>`;
                                    }
                                    let ndata = `<a href="`+r+`">`+ icon + data + `</a>`;
                                    return ndata;
                                }

                                return data;
                            }
                        },
                        {name: 'id'},
                        {name: 'mime_type'},
                        {
                            name: 'size',
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    let rowType = row["2"];
                                    if (rowType === 'directory') {
                                        return "N/A";
                                    } else {
                                        return formatters.humanFileSize(data);
                                    }
                                }

                                return data;
                            }
                        }
                    ],
                    columnDefs: [
                        {
                            targets: [1],
                            visible: false
                        }
                    ]
                });
            });
        </script>
    @endpush

@stop
