@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')

    <h3 class="text-center">Published Dataset Tags</h3>
    <br/>

    <table id="tags" class="table table-hover">
        <thead>
        <tr>
            <th>Tag</th>
            <th># Datasets</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tags as $tag => $count)
            <tr>
                <td>
                    <a class="badge text-bg-success fs-11 td-none text-white"
                       href="{{route('public.tags.search', ['tag' => $tag])}}">
                        {{$tag}}
                    </a>
                </td>
                <td>{{$count}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#tags').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
