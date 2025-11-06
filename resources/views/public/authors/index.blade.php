@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')

    <h3 class="text-center">Published Authors</h3>
    <br/>
    <table id="authors" class="table table-hover">
        <thead class="table-light">
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
