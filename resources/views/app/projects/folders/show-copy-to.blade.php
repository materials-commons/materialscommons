@extends('layouts.app')

@section('pageTitle', 'Files')

@section('content')
    <x-card>
        <x-slot name="header">
            <x-show-dir-path :project="$project" :file="$directory"/>
        </x-slot>

        <x-slot name="body">
            @if ($directory->path !== '/')
                <a href="{{route('projects.folders.show-for-copy', [$project, $fromDirectory, $directory->directory_id])}}"
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
                    @if($file->isDir())
                        <tr>
                            <td>
                                <a class="no-underline"
                                   href="{{route('projects.folders.show-for-copy', [$project, $fromDirectory, $file])}}">
                                    <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                </a>
                            </td>
                            <td>{{$file->mime_type}}</td>
                            @if($file->isDir())
                                <td></td>
                            @else
                                <td>{{$file->toHumanBytes()}}</td>
                            @endif
                            <td>{{$file->size}}</td>
                            <td>
                                <input type="checkbox" value="0">
                            </td>
                        </tr>
                    @endif
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