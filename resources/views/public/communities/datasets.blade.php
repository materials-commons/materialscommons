@extends('layouts.app')

@section('pageTitle', 'Community Datasets')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    <h3 class="text-center">Datasets for Community {{$community->name}}</h3>
    <br/>

    <table id="datasets" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Authors</th>
        </tr>
        </thead>
        <tbody>
        @foreach($community->publishedDatasets as $dataset)
            <tr>
                <td>
                    <a href="{{route('public.datasets.show', $dataset)}}">{{$dataset->name}}</a>
                </td>
                <td>{{$dataset->description}}</td>
                <td>{{collect($dataset->ds_authors)->implode('name', ', ')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#datasets').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });
    </script>
@endpush
