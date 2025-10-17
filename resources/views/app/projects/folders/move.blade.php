@extends('layouts.app')

@section('pageTitle', "{$project->name} - Files")

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

                <div class="mb-3">
                    <label for="project">Destination Project</label>
                    <select name="project" class="selectpicker col-lg-6"
                            data-style="btn-light no-tt"
                            title="Current project" data-live-search="true">
                        @foreach($projects as $project)
                            <option data-token="{{$project->id}}" value="{{$project->id}}">
                                {{$project->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
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
                           class="btn btn-info me-3">
                            Done
                        </a>

                        <a class="btn btn-success" onclick="document.getElementById('move-files').submit()" href="#">
                            Move Selected
                        </a>

                        <a class="btn btn-success" onclick="document.getElementById('move-files').submit()" href="#">
                            Copy Selected
                        </a>
                    </div>
                </div>
                <br/>
                <div class="mb-3">
                    <table id="files" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Selected?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td>
                                    @if($file->isDir())
                                        <a href="{{route('projects.folders.show', [$project, $file])}}">
                                            <i class="fa-fw fas fa-folder me-2"></i> {{$file->name}}
                                        </a>
                                    @else
                                        <a href="{{route('projects.files.show', [$project, $file])}}">
                                            <i class="fa-fw fas fa-file me-2"></i> {{$file->name}}
                                        </a>
                                    @endif
                                </td>
                                <td>{{$file->mime_type}}</td>
                                <td>
                                    @if(!$file->isDir())
                                        {{$file->toHumanBytes()}}
                                    @endif
                                </td>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{$file->id}}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

            @include('common.errors')

        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#files').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush

@stop
