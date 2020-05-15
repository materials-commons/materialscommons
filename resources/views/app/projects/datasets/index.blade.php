@extends('layouts.app')

@section('pageTitle', 'Datasets')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.index', $project))

@section('content')
    @component('components.card')
        @slot('header')
            Datasets
            <a class="action-link float-right" href="{{route('projects.datasets.create', [$project])}}">
                <i class="fas fa-plus mr-2"></i>Create Dataset
            </a>
        @endslot

        @slot('body')
            <table id="datasets" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Dataset</th>
                    <th>Summary</th>
                    <th>Tags</th>
                    <th>Published</th>
                    <th>Updated</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($datasets as $dataset)
                    <tr>
                        <td>
                            <a href="{{route('projects.datasets.show', [$project, $dataset])}}">{{$dataset->name}}</a>
                        </td>
                        <td>{{$dataset->summary}}</td>
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
                        <td>
                            <div class="float-right">
                                <a href="{{route('projects.datasets.show', [$project, $dataset])}}" class="action-link">
                                    <i class="fas fa-fw fa-eye"></i>
                                </a>
                                <a href="{{route('projects.datasets.edit', [$project, $dataset])}}" class="action-link">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>
                                <a href="{{route('projects.datasets.delete', [$project, $dataset])}}"
                                   class="action-link">
                                    <i class="fas fa-fw fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
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
                        {orderData: [5], targets: [4]},
                        {targets: [5], visible: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush
@stop
