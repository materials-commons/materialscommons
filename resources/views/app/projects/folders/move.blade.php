@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            @if (Request::routeIs('projects.folders.index'))
                Move files in {{$directory->name}}
            @else
                Move files in {{$directory->path}}
            @endif
        @endslot

        @slot('body')

            <form method="post" action="{{route('projects.folders.move.update', [$project, $directory])}}"
                  id="move-files">
                @csrf

                <div class="form-group">
                    <label for="directories">Move to directory</label>
                    <select name="directory" class="selectpicker col-lg-8"
                            title="directories" data-live-search="true">
                        @foreach($dirsInProject as $dir)
                            <option data-token="{{$dir->id}}" value="{{$dir->id}}">
                                {{$dir->path}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <table id="files" class="bootstrap-table bootstrap-table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Move?</th>
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
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{$file->id}}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="float-right">
                    <a href="{{route('projects.folders.show', [$project, $directory])}}"
                       class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link" onclick="document.getElementById('move-files').submit()" href="#">
                        Move Files
                    </a>
                </div>
            </form>

            @include('common.errors')

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
