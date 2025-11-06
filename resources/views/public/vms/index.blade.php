@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @isset($project)
    @else
        @include('layouts.navs.public')
    @endisset
@stop

@section('content')
    <br>
    <h4>
        The Virtual Machines below give you an easy way to access many of PRISMS Centers software packages.
    </h4>
    <br>
    <h3 class="text-center">Virtual Machines</h3>
    <br/>

    <table id="datasets" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Downloads</th>
            <th>Published</th>
            <th>Summary</th>
            <th>Authors</th>
        </tr>
        </thead>
    </table>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#vms').DataTable({pageLength: 100});
            });
        </script>
    @endpush
@stop
