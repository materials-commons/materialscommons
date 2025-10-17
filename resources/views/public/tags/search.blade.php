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
            <x-table-container>
                <table id="datasets" class="table table-hover" style="width:100%">
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
                            <td>{{collect($dataset->ds_authors)->implode('name', ', ')}}</td>
                            <td>
                                @foreach($dataset->tags as $tag)
                                    <span class="badge badge-info ms-1">{{$tag->name}}</span>
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
            </x-table-container>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#datasets').DataTable({
                    pageLength: 100,
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
