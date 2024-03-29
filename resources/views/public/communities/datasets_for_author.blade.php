@extends('layouts.app')

@section('pageTitle', 'Public Data Community')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Datasets for Author: {{$author}}
        @endslot

        @slot('body')
            <h3>Dataset including author in community: {{$community->name}}</h3>
            <br/>
            <table id="author-matches-in-community" class="table table-hover">
                <thead>
                <tr>
                    <th>Dataset</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Downloads</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datasetsFromCommunity as $ds)
                    <tr>
                        <td>
                            <a href="{{route('public.datasets.show', [$ds])}}">{{$ds->name}}</a>
                        </td>
                        <td>
                            <a href="{{route('public.authors.search', ['search' => $ds->owner->name])}}">{{$ds->owner->name}}</a>
                        </td>
                        <td>{{$ds->views_count}}</td>
                        <td>{{$ds->downloads_count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br/>
            <br/>
            <h3>Dataset including author that are not in community: {{$community->name}}</h3>
            <br/>
            <table id="author-matches-not-in-community" class="table table-hover">
                <thead>
                <tr>
                    <th>Dataset</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Downloads</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datasetsNotFromCommunity as $ds)
                    <tr>
                        <td>
                            <a href="{{route('public.datasets.show', [$ds])}}">{{$ds->name}}</a>
                        </td>
                        <td>
                            <a href="{{route('public.authors.search', ['search' => $ds->owner->name])}}">{{$ds->owner->name}}</a>
                        </td>
                        <td>{{$ds->views_count}}</td>
                        <td>{{$ds->downloads_count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#author-matches-in-community').DataTable({
                pageLength: 100,
                stateSave: true,
            });
            $('#author-matches-not-in-community').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });
    </script>
@endpush
