@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Datasets with tag: {{$tag}}
        @endslot

        @slot('body')
            <table id="datasets" class="bootstrap-table bootstrap-table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Dataset</th>
                    <th>Description</th>
                    <th>Authors</th>
                    <th>Tags</th>
                    <th>Published</th>
                    <th>Updated</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datasets as $dataset)
                    <tr>
                        <td>
                            <a href="{{route('public.datasets.show', [$dataset])}}">{{$dataset->name}}</a>
                        </td>
                        <td>{{$dataset->description}}</td>
                        <td>{{$dataset->authors}}</td>
                        <td>
                            @foreach($dataset->tags as $tag)
                                <span class="badge badge-info ml-1">{{$tag->name}}</span>
                            @endforeach
                        </td>
                        @if ($dataset->published_at === null)
                            <td>Not published</td>
                        @else
                            <td>{{$dataset->published_at->diffForHumans()}}</td>
                        @endif
                        <td>{{$dataset->updated_at->diffForHumans()}}</td>
                        <td>{{$dataset->updated_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#datasets').DataTable({
                    stateSave: true,
                    columnDefs: [
                        {orderData: [6], targets: [5]},
                        {targets: [6], visible: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush
@endsection