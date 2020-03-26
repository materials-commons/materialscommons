@extends('layouts.app')

@section('pageTitle', 'Communities')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Communities
            <a class="action-link float-right" href="{{route('communities.create')}}">
                <i class="fas fa-plus mr-2"></i>Create Community
            </a>
        @endslot

        @slot('body')
            <table id="communities" class="table table-hover">
                <thead>
                <tr>
                    <th>Community</th>
                    <th>Summary</th>
                    <th>Updated</th>
                    <th>Public?</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($communities as $community)
                    <tr>
                        <td>
                            <a href="{{route('communities.show', [$community])}}" class="action-link">
                                {{$community->name}}
                            </a>
                        </td>
                        <td>{{$community->summary}}</td>
                        <td>{{$community->updated_at->diffForHumans()}}</td>
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
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            mcutil.setupDatatableOnDocumentReady('#communities');
        </script>
    @endpush
@stop