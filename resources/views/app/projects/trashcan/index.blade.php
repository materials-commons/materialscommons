@extends('layouts.app')

@section('pageTitle', "{$project->name} - Trashcan")

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
            <x-card-container>
                <div class="float-end">
                    @if($nav_trash_count > 0)
                        <a data-bs-toggle="modal" href="#empty-trash-modal" class="btn btn-danger">Empty Trash</a>
                    @else
                        <a href="#" class="btn btn-danger disabled" disabled>Empty Trash</a>
                    @endif
                </div>
                @include('app.projects.trashcan._empty-trash-modal')
                <br>
                <br>
                <br>
                <table id="trash" class="table table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th>File/Directory</th>
                        <th>Will be deleted in</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($trash as $item)
                        <tr>
                            @if(blank($item->path))
                                <td>
                                    <a href="{{route('projects.files.show', [$project, $item])}}">
                                        <i class="fa-fw fas fa-file me-2"></i> {{$item->getFilePath()}}
                                    </a>
                                </td>
                            @else
                                <td>
                                    <a href="{{route('projects.folders.show', [$project, $item])}}">
                                        <i class="fa-fw fas fa-folder me-2"></i> {{$item->path}}
                                    </a>
                                </td>
                            @endif
                            <td>{{$item->expiresInDays()}} days</td>
                            <td>
                                <div class="float-end">
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
                                            @if(blank($item->path))
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
            </x-card-container>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            document.addEventListener('livewire:navigating', () => {
                $('#trash').DataTable().destroy();
            }, {once: true});

            $(document).ready(() => {
                $('#trash').DataTable({
                    pageLength: 100,
                    // stateSave: true,
                });
            });
        </script>
    @endpush

@stop
