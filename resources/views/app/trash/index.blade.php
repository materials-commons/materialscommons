@extends('layouts.app')

@section('pageTitle', 'Trash')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Deleted Projects
        </x-slot>
        <x-slot name="body">
            <table id="projects-trash" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Project</th>
                    <th>Size</th>
                    <th>Hidden Size</th>
                    <th>Files</th>
                    <th>Samples</th>
                    <th>Owner</th>
                    <th>Updated</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($projects as $proj)
                    <tr>
                        <td>{{$proj->name}}</td>
                        <td>{{formatBytes($proj->size)}}</td>
                        <td>{{$proj->size}}</td>
                        <td>{{number_format($proj->file_count)}}</td>
                        <td>{{number_format($proj->entities_count)}}</td>
                        <td>{{$proj->owner->name}}</td>
                        <td>{{$proj->updated_at->diffForHumans()}}</td>
                        <td>{{$proj->updated_at}}</td>
                        <td>
                            <a href="{{route('trash.project.restore', [$proj])}}" class="action-link">
                                <i class="fas fa-fw fa-trash-restore"></i>
                                restore
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-slot>
    </x-card>
@endsection

@push('scripts')
    <script>
        $('#projects-trash').DataTable({
            stateSave: true,
            columnDefs: [
                {orderData: [7], targets: [6]},
                {targets: [7], visible: false, searchable: false},
                {orderData: [2], targets: [1]},
                {targets: [2], visible: false},
            ]
        });
    </script>
@endpush