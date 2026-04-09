@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Project")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    Showing desktop {{$hostname}} ({{$desktopId}}), directory {{$dir}}
    <br/>
    <br/>
    <div class="d-flex justify-content-center gap-4">
        <a href="{{route('projects.desktops.submit-test-upload', [$project, $desktopId])}}" class="btn btn-success">
            Upload All
        </a>
        <a href="{{route('projects.desktops.submit-test-upload', [$project, $desktopId])}}"
           class="btn btn-success">
            Download All
        </a>

        <a href="{{route('projects.desktops.submit-test-upload', [$project, $desktopId])}}"
           class="btn btn-danger">
            Synchronize
        </a>

        <a href="{{route('projects.desktops.submit-test-upload', [$project, $desktopId])}}"
           class="btn btn-success">
            Find Files
        </a>
        <a href="{{route('projects.desktops.submit-test-upload', [$project, $desktopId])}}"
           class="btn btn-success">
            Search Files
        </a>
    </div>
    <br/>
    <br/>
    <table id="desktop-client" class="table table-hover" style="width: 100%">
        <thead>
        <tr>
            <th>File</th>
            <th>L_Type</th>
            <th>R_Type</th>
            <th>Local/Remote</th>
            <th>Action</th>
            <th>Reason</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clientFiles as $file)
            <tr>
                <td>
                    @if($file->r_type == "D")
                        @php
                            if ($dir == "/") {
                                $fileDir = "{$dir}{$file->name}";
                            } else {
                                $fileDir = "{$dir}/{$file->name}";
                            }
                        @endphp
                        <a href="{{route('projects.desktops.show', [$project, $desktopId, $hostname, 'dir' => "{$fileDir}"])}}">
                            {{$file->name}}
                        </a>
                    @else
                        {{$file->name}}
                    @endif
                </td>
                <td>{{$file->l_type}}</td>
                <td>{{$file->r_type}}</td>
                <td>{{$file->local_remote}}</td>
                <td>{{$file->action}}</td>
                <td>{{$file->reason}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{--    <br/>--}}
    {{--    <br/>--}}
    {{--    <a href="{{route('projects.desktops.submit-test-upload', [$project, $desktopId])}}" class="btn btn-success">--}}
    {{--        Submit Test Upload--}}
    {{--    </a>--}}

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#desktop-client').DataTable({
                    pageLength: 100,
                });
            });
        </script>
    @endpush
@stop
