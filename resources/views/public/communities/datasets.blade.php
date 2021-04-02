@extends('layouts.app')

@section('pageTitle', 'Community Datasets')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Datasets for Community {{$community->name}}
        @endslot

        @slot('body')
            <table id="datasets" class="bootstrap-table bootstrap-table-hover" style="width:100%">
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
                        <td>{{$dataset->authors}}</td>
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
            $('#datasets').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush
