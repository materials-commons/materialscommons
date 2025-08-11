@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Published Authors
        @endslot

        @slot('body')
            <x-table-container>
                <table id="authors" class="table table-hover">
                    <thead>
                    <tr>
                        <th>Author</th>
                        <th># Datasets</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($authors as $author => $count)
                        <tr>
                            <td>
                                <a href="{{route('public.authors.search', ['search' => $author])}}">{{$author}}</a>
                            </td>
                            <td>{{$count}}</td>
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
                $('#authors').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
