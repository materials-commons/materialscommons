@extends('layouts.app')

@section('pageTitle', 'Files')

@section('content')
    <x-card>
        <x-slot name="header">
            <x-show-dir-path :project="$project" :file="$directory"/>

            <a class="float-right action-link mr-4"
               href="{{route('projects.folders.index-images', [$project, $directory])}}">
                <i class="fas fa-fw fa-images mr-2"></i>View Images
            </a>
        </x-slot>

        <x-slot name="body">
            @if ($directory->path !== '/')
                <a href="{{route('projects.folders.show-for-copy', [$project, $directory->directory_id])}}"
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
                    <th>Move Here</th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>
                            @if($file->isDir())
                                <a class="no-underline"
                                   href="{{route('projects.folders.show-for-copy', [$project, $file])}}">
                                    <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                </a>
                            @else
                                <a class="no-underline" href="{{route('projects.files.show', [$project, $file])}}">
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
                                <input type="checkbox" value="0">
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