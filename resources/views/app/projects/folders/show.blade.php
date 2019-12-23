@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            @if (Request::routeIs('projects.folders.index'))
                {{$directory->name}}
            @else
                @if(sizeof($dirPaths) == 1)
                    {{$directory->name}}
                @else
                    @foreach($dirPaths as $dirpath)
                        <a class="action-link"
                           href="{{route('projects.folders.by_path', ['project' => $project, 'path' => $dirpath["path"]])}}">
                            {{$dirpath['name']}}/
                        </a>
                    @endforeach
                @endif
            @endif
            <a class="float-right action-link" href="#">
                <i class="fas fa-trash mr-2"></i>Delete Files
            </a>

            <a class="float-right action-link mr-4" href="{{route('projects.folders.move', [$project, $directory])}}">
                <i class="fas fa-angle-double-right mr-2"></i>Move Files
            </a>

            <a class="float-right action-link mr-4"
               href="{{route('projects.folders.upload', [$project->id, $directory->id])}}">
                <i class="fas fa-fw fa-plus mr-2"></i>Add Files
            </a>

            <a class="float-right action-link mr-4"
               href="{{route('projects.folders.create', [$project, $directory])}}">
                <i class="fas fa-fw fa-folder-plus mr-2"></i>Create Directory
            </a>
        @endslot

        @slot('body')
            <table id="files" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>
                            @if($file->isDir())
                                <a href="{{route('projects.folders.show', [$project, $file])}}">
                                    <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                </a>
                            @else
                                <a href="{{route('projects.files.show', [$project, $file])}}">
                                    <i class="fa-fw fas fa-file mr-2"></i> {{$file->name}}
                                </a>
                            @endif
                        </td>
                        <td>{{$file->mime_type}}</td>
                        <td>{{$file->toHumanBytes()}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#files').DataTable({
                    stateSave: true,
                });
            });
        </script>
    @endpush

@stop
