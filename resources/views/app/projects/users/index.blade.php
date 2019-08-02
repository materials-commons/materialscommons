@extends('layouts.app')

@section('pageTitle', 'Users')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Users
        @endslot

        @slot('body')

            <table id="users" class="table" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                </thead>
            </table>
        @endslot
    @endcomponent


    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#users').DataTable({
                    serverSide: true,
                    processing: true,
                    responsive: true,
                    ajax: "{{ route('get_users') }}",
                    columns: [
                        {name: 'name'},
                        {name: 'email'}
                    ],
                });
            });
        </script>
    @endpush
@stop
