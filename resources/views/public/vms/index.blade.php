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
    @component('components.card')
        @slot('header')
            Virtual Machines
        @endslot

        @slot('body')
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
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#vms').DataTable({pageLength: 100});
            });
        </script>
    @endpush
@stop
