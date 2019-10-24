@extends('layouts.app')

@section('pageTitle', 'Dataset Files')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.files.edit', $project, $dataset))

@section('content')
    @component('components.card')
        @slot('header')
            <span class="float-right">Dataset: {{$dataset->name}}</span>
            @if (Request::routeIs('projects.datasets.folders.index'))
                {{$directory->name}}
            @else
                {{$directory->path}}
            @endif
        @endslot

        @slot('body')
            <table id="files" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Selected</th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>
                            @if ($file->mime_type === 'directory')
                                <a href="{{route('projects.datasets.folders.show', [$project, $dataset, $file])}}">
                                    <i class="fa-fw fas mr-2 fa-folder"></i> {{$file->name}}
                                </a>
                            @else
                                <a href="{{route('projects.files.show', [$project, $file])}}">
                                    <i class="fa-fw fas mr-2 fa-file"></i>{{$file->name}}
                                </a>
                            @endif
                        </td>
                        <td>{{$file->mime_type}}</td>
                        @if ($file->mime_type === 'directory')
                            <td>N/A</td>
                        @else
                            <td>{{$file->toHumanBytes()}}</td>
                        @endif
                        <td>
                            <div class="form-group form-check-inline">
                                <input type="checkbox" class="form-check-input" id="{{$file->uuid}}"
                                       {{$file->selected ? 'checked' : ''}}
                                       onclick="updateSelection({{$file}}, this)">
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
            let projectId = "{{$project->id}}";
            let datasetId = "{{$dataset->id}}";
            let directoryPath = "{{$directory->path}}";
            let route = "{{route('projects.datasets.selection', [$dataset])}}";
            let apiToken = "{{$user->api_token}}";

            $(document).ready(() => {
                $('#files').DataTable({
                    stateSave: true,
                });
            });

            function updateSelection(file, checkbox) {
                if (checkbox.checked) {
                    if (file.mime_type === 'directory') {
                        addDirectory(file);
                    } else {
                        addFile(file);
                    }

                } else {
                    if (file.mime_type === 'directory') {
                        removeDirectory(file);
                    } else {
                        removeFile(file);
                    }
                }
            }

            function addDirectory(dir) {
                axios.put(`${route}?api_token=${apiToken}`, {
                    project_id: projectId,
                    include_dir: `${dir.path}`
                }).then(
                    () => null
                );
            }

            function addFile(file) {
                axios.put(`${route}?api_token=${apiToken}`, {
                    project_id: projectId,
                    include_file: `${directoryPath}/${file.name}`
                }).then(
                    () => null
                );
            }

            function removeDirectory(dir) {
                axios.put(`${route}?api_token=${apiToken}`, {
                    project_id: projectId,
                    remove_include_dir: `${dir.path}`
                }).then(
                    () => null
                );
            }

            function removeFile(file) {
                axios.put(`${route}?api_token=${apiToken}`, {
                    project_id: projectId,
                    remove_include_file: `${directoryPath}/${file.name}`
                }).then(
                    () => null
                );
            }

        </script>
    @endpush

@stop
