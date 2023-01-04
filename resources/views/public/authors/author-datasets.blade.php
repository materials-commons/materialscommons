@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Datasets for author: {{$author}}
        @endslot

        @slot('body')
            <table id="datasets" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Views</th>
                    <th>Downloads</th>
                    <th>Published</th>
                    <th>Summary</th>
                    <th>Authors</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datasets as $dataset)
                    <tr>
                        <td>
                            <a href="{{route('public.datasets.show', [$dataset])}}">{{$dataset->name}}</a>
                        </td>
                        <td>{{$dataset->views_count}}</td>
                        <td>{{$dataset->downloads_count}}</td>
                        <td>{{$dataset->published_at->toDateString()}}</td>
                        <td>{{$dataset->summary}}</td>
                        <td>{{collect($dataset->ds_authors)->implode('name', ', ')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#datasets').DataTable({pageLength: 100});
        });
    </script>
@endpush
