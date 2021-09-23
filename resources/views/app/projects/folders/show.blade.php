@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
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
            {{--            <a class="float-right action-link" href="#">--}}
            {{--                <i class="fas fa-trash mr-2"></i>Delete Files--}}
            {{--            </a>--}}

            <a class="float-right action-link mr-4"
               href="{{route('projects.folders.move', [$project, $directory])}}">
                <i class="fas fa-angle-double-right mr-2"></i>Move Files
            </a>

            {{--            @if($directory->name == "/")--}}
            {{--                <a class="float-right action-link mr-4"--}}
            {{--                   href="{{route('projects.folders.move', [$project, $directory])}}">--}}
            {{--                    <i class="fas fa-angle-double-right mr-2"></i>Move Files--}}
            {{--                </a>--}}
            {{--            @else--}}
            {{--                <a class="float-right action-link mr-4" href="#">--}}
            {{--                    <i class="fas fa-trash mr-2"></i> Delete Directory--}}
            {{--                </a>--}}
            {{--                <a class="float-right action-link"--}}
            {{--                   href="{{route('projects.folders.move', [$project, $directory])}}">--}}
            {{--                    <i class="fas fa-angle-double-right mr-2"></i>Move Files--}}
            {{--                </a>--}}
            {{--            @endif--}}

            <a class="float-right action-link mr-4"
               href="{{route('projects.folders.upload', [$project->id, $directory->id])}}">
                <i class="fas fa-fw fa-plus mr-2"></i>Add Files
            </a>

                <a class="float-right action-link mr-4"
                   href="{{route('projects.folders.create', [$project, $directory])}}">
                    <i class="fas fa-fw fa-folder-plus mr-2"></i>Create Directory
                </a>

                @if(sizeof($dirPaths) !== 1 && $files->count() === 0)
                    <a class="float-right action-link mr-4"
                       href="{{route('projects.folders.destroy', [$project, $directory])}}">
                        <i class="fas fa-fw fa-trash mr-2"></i>Delete
                    </a>
                @endif

                @if(sizeof($dirPaths) !== 1)
                    <a class="float-right action-link mr-4"
                       href="{{route('projects.folders.rename', [$project, $directory])}}">
                        <i class="fas fa-fw fa-edit mr-2"></i>Rename
                    </a>
                @endif
        </x-slot>

        <x-slot name="body">
            @if ($directory->path == '/')
                <span class="float-left action-link mr-4">
                    <i class="fa-fw fas fa-filter mr-2"></i>
                    Filter:
                </span>
                {{--                <a class="float-left action-link mr-4" href="#">--}}
                {{--                    <i class="fa-fw fas fa-calendar mr-2"></i>--}}
                {{--                    By Date--}}
                {{--                </a>--}}

                <a class="float-left action-link" href="{{route('projects.folders.filter.by-user', [$project])}}">
                    <i class="fa-fw fas fa-user-friends mr-2"></i>
                    By User
                </a>

                <br>
                <br>
            @endif

            @if ($directory->path !== '/')
                <a href="{{route('projects.folders.show', [$project, $directory->directory_id])}}"
                   class="mb-3">
                    <i class="fa-fw fas fa-arrow-alt-circle-up mr-2"></i>Go up one level
                </a>
                <br>
                <br>
            @endif

            <table id="files" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Real Size</th>
                    <th></th>
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
                        @if($file->isDir())
                            <td></td>
                        @else
                            <td>{{$file->toHumanBytes()}}</td>
                        @endif
                        <td>{{$file->size}}</td>
                        <td>
                            @if($file->isImage())
                                <a href="{{route('projects.files.display', [$project, $file])}}">

                                    <img src="{{route('projects.files.display', [$project, $file])}}"
                                         style="width: 12rem">
                                </a>
                            @elseif($file->isDir())
                                <a class="action-link"
                                   href="{{route('projects.folders.destroy', [$project, $file])}}">
                                    <i class="fas fa-fw fa-trash mr-2"></i>
                                </a>
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-slot>
    </x-card>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#files').DataTable({
                    stateSave: true,
                    columnDefs: [
                        {orderData: [3], targets: [2]},
                        {targets: [3], visible: false},
                    ]
                });
            });
        </script>
    @endpush

@stop
