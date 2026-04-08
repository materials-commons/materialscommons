@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Project")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    Showing desktop spelljammer ({{$desktopId}})
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
        <tr>
            <td>
                <a href="#">Aging_Test.xlsx</a>
            </td>
            <td>F</td>
            <td>F</td>
            <td>L/R</td>
            <td>skip</td>
            <td>local content matches remote</td>
        </tr>

        <tr>
            <td>
                <a href="#">CameraPhotos</a>
            </td>
            <td>D</td>
            <td>D</td>
            <td>L/R</td>
            <td>skip</td>
            <td>local and remote directories exist</td>
        </tr>

        <tr>
            <td>
                <a href="#">D1</a>
            </td>
            <td>D</td>
            <td>D</td>
            <td>L/R</td>
            <td>skip</td>
            <td>local and remote directories exist</td>
        </tr>

        <tr>
            <td>
                <a href="#">Image1-As-cast Mg-2Nd-4Y-0.5Ca.tif</a>
            </td>
            <td>F</td>
            <td>F</td>
            <td>L/R</td>
            <td>skip</td>
            <td>local content matches remote</td>
        </tr>

        <tr>
            <td>
                <a href="#">Literature</a>
            </td>
            <td>-</td>
            <td>D</td>
            <td>R</td>
            <td><a href="#">Download</a></td>
            <td>local directory is missing</td>
        </tr>

        <tr>
            <td>
                <a href="#">MC2Upload</a>
            </td>
            <td>D</td>
            <td>D</td>
            <td>L/R</td>
            <td>skip</td>
            <td>local and remote directories exist</td>
        </tr>

        <tr>
            <td>
                <a href="#">mcapi_error.json</a>
            </td>
            <td>F</td>
            <td>F</td>
            <td>L/R</td>
            <td><a href="#">conflict</a></td>
            <td>remote and local diverged</td>
        </tr>

        <tr>
            <td>
                <a href="#">local.txt</a>
            </td>
            <td>F</td>
            <td>-</td>
            <td>L</td>
            <td><a href="#">upload</a></td>
            <td>remote file is missing; preserve local file</td>
        </tr>
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
