@extends('layouts.app')

@section('pageTitle', 'Search Results')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Search results for {{$search}}
        @endslot

        @slot('body')
            <table class="table table-hover" id="dt-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Project</th>
                    <th>Type</th>
                </tr>
                </thead>
                <tbody>
                @foreach($searchResults->groupByType() as $type => $modelSearchResults)
                    @foreach($modelSearchResults as $searchResult)
                        <tr>
                            <td>
                                <a href="{{$searchResult->url}}">{{$searchResult->title}}</a>
                            </td>
                            <td>{{$searchResult->searchable->description}}</td>
                            <td>
                                @if($searchResult->searchable->type != 'project' && $searchResult->searchable->type != 'community')
                                    <a href="{{route('projects.show', [$searchResult->searchable->project])}}">{{$searchResult->searchable->project->name}}</a>
                                @endif
                            </td>
                            <td>{{$searchResult->searchable->type}}</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#dt-table').DataTable({
                language: {
                    search: "Filter:"
                }
            });
        });
    </script>
@endpush
