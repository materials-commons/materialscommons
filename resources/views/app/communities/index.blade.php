@extends('layouts.app')

@section('pageTitle', 'Communities')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Communities
            <a class="action-link float-end" href="{{route('communities.create')}}">
                <i class="fas fa-plus me-2"></i>Create Community
            </a>
        @endslot

        @slot('body')
            <x-table-container>
                <table id="communities" class="table table-hover">
                    <thead>
                    <tr>
                        <th>Community</th>
                        <th>Summary</th>
                        <th>Updated</th>
                        <th>Date</th>
                        <th>Public?</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($communities as $community)
                        <tr>
                            <td>
                                <a href="{{route('communities.show', [$community])}}" class="">
                                    {{$community->name}}
                                </a>
                            </td>
                            <td>{{$community->summary}}</td>
                            <td>{{$community->updated_at->diffForHumans()}}</td>
                            <td>{{$community->updated_at}}</td>
                            <td>{{$community->public ? "Yes" : "No"}}</td>
                            <td>
                                @include('partials.table_row_controls', [
                                        'showRoute' => route('communities.show', [$community]),
                                        'editRoute' => route('communities.edit', [$community]),
                                        'deleteRoute' => route('communities.delete', [$community])
                                ])
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-table-container>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#communities').DataTable({
                    pageLength: 100,
                    stateSave: true,
                    columnDefs: [
                        {orderData: [3], targets: [2]},
                        {targets: [3], visible: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush
@stop
