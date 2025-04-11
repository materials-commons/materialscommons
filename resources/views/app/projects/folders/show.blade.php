@extends('layouts.app')

@section('pageTitle', "{$project->name} - Files")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            <x-show-dir-path :project="$project" :file="$directory"/>
        </x-slot>

        <x-slot name="body">
            <x-projects.folders.controls :project="$project" :directory="$directory" :scripts="$scripts"
                                         arg="{{$arg}}"/>
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
                <a href="{{route('projects.folders.show', [$project, $directory->directory_id, 'destproj' => $destinationProject->id])}}"
                   class="mb-3">
                    <i class="fa-fw fas fa-arrow-alt-circle-up mr-2"></i>Go up one level
                </a>
                <br>
                <br>
            @endif

            <form method="post" action="{{route('projects.folders.move.update', [$project, $directory])}}"
                  id="move-copy-files">
                @csrf

                @if($arg == 'move-copy')
                    <div class="form-group">
                        <label for="project">Destination Project</label>
                        <select name="project" class="selectpicker col-lg-6" id="select-project"
                                data-style="btn-light no-tt"
                                title="Current project" data-live-search="true">
                            @foreach($projects as $p)
                                <option data-token="{{$p->id}}"
                                        value="{{$p->id}}" @selected($p->id == $destinationProject->id)>
                                    @if($p->id == $project->id)
                                        This Project ({{$p->name}})
                                    @else
                                        {{$p->name}}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="directories">Destination</label>
                        <select name="directory" class="selectpicker col-lg-6"
                                data-style="btn-light no-tt"
                                title="Select directory" data-live-search="true">
                            @foreach($dirsInProject as $dir)
                                <option data-token="{{$dir->id}}" value="{{$dir->id}}">
                                    {{$dir->path}}
                                </option>
                            @endforeach
                        </select>
                        <div class="float-right">
                            <a href="{{route('projects.folders.show', [$project, $directory])}}"
                               class="btn btn-info mr-3">
                                Done
                            </a>

                            <a class="btn btn-success" onclick="moveFiles()"
                               href="#">
                                Move Selected
                            </a>

                            <a class="btn btn-success" onclick="copyFiles()"
                               href="#">
                                Copy Selected
                            </a>
                        </div>
                    </div>
                    <br/>
                @endif

                <table id="files" class="table table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Real Size</th>
                        <th>Last Updated</th>
                        <th>Real Updated</th>
                        <th>Thumbnail</th>
                        @if($arg == 'move-copy')
                            <th>Select</th>
                        @endif
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($files as $file)
                        <tr>
                            <td>
                                @if($file->isDir())
                                    <a class="no-underline"
                                       href="{{route('projects.folders.show', [$project, $file, 'destproj' => $destinationProject->id, 'arg' => $arg])}}">
                                        <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                    </a>
                                @else
                                    <a class="no-underline" href="{{route('projects.files.show', [$project, $file])}}">
                                        <i class="fa-fw fas fa-file mr-2"></i> {{$file->name}}
                                    </a>
                                @endif
                            </td>
                            <td>{{$file->mimeTypeToDescriptionForDisplay($file)}}</td>
                            @if($file->isDir())
                                <td></td>
                            @else
                                <td>{{$file->toHumanBytes()}}</td>
                            @endif
                            <td>{{$file->size}}</td>
                            <td>{{$file->created_at->diffForHumans()}}</td>
                            <td>{{$file->created_at}}</td>
                            <td>
                                @if($file->isImage())
                                    <a href="{{route('projects.files.display', [$project, $file])}}">

                                        <img src="{{route('projects.files.display', [$project, $file])}}"
                                             style="width: 12rem">
                                    </a>
                                @endif
                            </td>
                            @if($arg == 'move-copy')
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{$file->id}}">
                                </td>
                            @endif
                            <td>
                                @if($file->isDir())
                                    <a class="action-link" title="Delete directory"
                                       href="{{route('projects.folders.delete', [$project, $file, 'destproj' => $destinationProject->id, 'arg' => $arg])}}">
                                        <i class="fas fa-fw fa-trash mr-2"></i>
                                    </a>
                                @else
                                    <a class="action-link" title="Delete file"
                                       href="{{route('projects.files.destroy', [$project, $file, 'destproj' => $destinationProject->id, 'arg' => $arg])}}">
                                        <i class="fas fa-fw fa-trash mr-2"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
            <x-display-markdown-file :file="$readme"></x-display-markdown-file>
        </x-slot>
    </x-card>

    @include('app.dialogs._copy-choose-project-dialog')

    @if($scripts->count() != 0)
        @include('app.dialogs._select-script-dialog')
    @endif

    @push('scripts')
        <script>
            document.addEventListener('livewire:navigating', () => {
                $('#files').DataTable().destroy();
            }, {once: true});

            function moveFiles() {
                let choosenProjectId = $('#select-project').val();
                let moveRoute = route('projects.folders.move.update', {
                    'project': {{$project->id}},
                    'folder': {{$directory->id}},
                    'destproj': choosenProjectId,
                    'arg': 'move-copy',
                });
                let form = document.getElementById('move-copy-files');
                form.action = moveRoute;
                form.submit();
            }

            function copyFiles() {
                let choosenProjectId = $('#select-project').val();
                let copyRoute = route('projects.folders.copy-to', {
                    'project': {{$project->id}},
                    'folder': {{$directory->id}},
                    'destproj': choosenProjectId,
                    'arg': 'move-copy',
                });
                let form = document.getElementById('move-copy-files');
                form.action = copyRoute;
                form.submit();
            }

            $(document).ready(() => {
                $('#files').DataTable({
                    pageLength: 100,
                    stateSave: true,
                    columnDefs: [
                        {orderData: [3], targets: [2]},
                        {targets: [3], visible: false},
                        {orderData: [5], targets: [4]},
                        {targets: [5], visible: false},
                    ]
                });

                $('#select-project').on('change', function () {
                    let choosenProjectId = $(this).val();
                    window.location.href = route('projects.folders.show', {
                        'project': {{$project->id}},
                        'folder': {{$directory->id}},
                        'destproj': choosenProjectId,
                        'arg': 'move-copy',
                    });
                });
            });
        </script>
    @endpush

@stop
