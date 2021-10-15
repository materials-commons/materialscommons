@extends('layouts.app')

@section('pageTitle', 'Samples')

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.entities.index', $project))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Trashcan
        @endslot

        @slot('body')
            @if(false)
                @if($nav_trash_count > 0)
                    <a href="#" class="btn btn-danger">Empty Trash</a>
                @else
                    <a href="#" class="btn btn-danger disabled" disabled>Empty Trash</a>
                @endif
                <br>
                <br>
            @endif
            <table id="trash" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>File/Directory</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($trash as $item)
                    <tr>
                        @if(is_null($item->path))
                            <td>
                                <a href="{{route('projects.files.show', [$project, $item])}}">
                                    <i class="fa-fw fas fa-file mr-2"></i> {{$item->getFilePath()}}
                                </a>
                            </td>
                        @else
                            <td>
                                <a href="{{route('projects.folders.show', [$project, $item])}}">
                                    <i class="fa-fw fas fa-folder mr-2"></i> {{$item->path}}
                                </a>
                            </td>
                        @endif
                        <td>
                            <div class="float-right">
                                <ul class="list-unstyled">
                                    @if(false)
                                        <li>
                                            <a href="#" class="action-link">
                                                <i class="fas fa-fw fa-trash"></i>
                                                delete
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        @if(is_null($item->path))
                                            <a href="{{route('projects.trashcan.file.restore', [$project, $item])}}"
                                               class="action-link">
                                                <i class="fas fa-fw fa-trash-restore"></i>
                                                restore
                                            </a>
                                        @else
                                            <a href="{{route('projects.trashcan.dir.restore', [$project, $item])}}"
                                               class="action-link">
                                                <i class="fas fa-fw fa-trash-restore"></i>
                                                restore
                                            </a>
                                        @endif
                                    </li>
                                </ul>
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
            $(document).ready(() => {
                $('#trash').DataTable({
                    // stateSave: true,
                });
            });
        </script>
    @endpush
@stop