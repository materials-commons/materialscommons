@extends('layouts.app')

@section('pageTitle', 'Projects')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.index'))

@section('content')
    @component('components.card')
        @slot('header')
            Projects
            <a class="action-link float-right"
               href="{{route('projects.create')}}">
                <i class="fas fa-plus me-2"></i>Create Project
            </a>
        @endslot

        @slot('body')
            <h4>Projects</h4>
            <br>
            <table id="projects" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Project</th>
                    <th>Summary</th>
                    <th>Owner</th>
                    <th>Updated</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($projects as $proj)
                    <tr>
                        <td>
                            <a href="{{route('projects.show', [$proj->id])}}" class="">{{$proj->name}}</a>
                        </td>
                        <td>{{$proj->summary}}</td>
                        <td>{{$proj->owner->name}}</td>
                        <td>{{$proj->updated_at->diffForHumans()}}</td>
                        <td>{{$proj->updated_at}}</td>
                        <td>
                            <div class="float-right">
                                <a href="{{route('projects.show', [$proj->id])}}" class="action-link">
                                    <i class="fas fa-fw fa-eye"></i>
                                </a>
                                <a href="{{route('projects.edit', [$proj->id])}}" class="action-link">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>
                                @if(auth()->id() == $proj->owner_id)
                                    <a data-bs-toggle="modal" href="#project-delete-{{$proj->id}}"
                                       class="action-link">
                                        <i class="fas fa-fw fa-trash-alt"></i>
                                    </a>
                                @endif
                            </div>
                            @if(auth()->id() == $proj->owner_id)
                                @component('app.projects.delete-project', ['project' => $proj])
                                @endcomponent
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @component('components.card')
        @slot('header')
            Published Datasets
        @endslot
        @slot('body')
            <h4>Published Datasets</h4>
            <br>
            <table id="datasets" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Dataset</th>
                    <th>Summary</th>
                    <th>Published</th>
                    <th>Date</th>
                    <th>Views</th>
                    <th>Downloads</th>
                    <th>Comments</th>
                </tr>
                </thead>
                <tbody>
                @foreach($publishedDatasets as $ds)
                    <tr>
                        <td>
                            <a class="action-link" href="{{route('public.datasets.show', [$ds])}}">
                                {{$ds->name}}
                            </a>
                        </td>
                        <td>{{$ds->summary}}</td>
                        <td>{{$ds->published_at->diffForHumans()}}</td>
                        <td>{{$ds->published_at}}</td>
                        <td>{{$ds->views_count}}</td>
                        <td>{{$ds->downloads_count}}</td>
                        <td>{{$ds->comments_count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            let projectsCount = "{{sizeof($projects)}}";
            $(document).ready(() => {
                if (projectsCount == 0) {
                    $('#welcome-dialog').modal();
                }
                $('#projects').DataTable({
                    pageLength: 100,
                    stateSave: true,
                    columnDefs: [
                        {orderData: [4], targets: [3]},
                        {targets: [4], visible: false, searchable: false},
                    ]
                });

                $('#datasets').DataTable({
                    pageLength: 100,
                    stateSave: true,
                    columnDefs: [
                        {orderData: [3], targets: [2]},
                        {targets: [3], visible: false, searchable: false},
                    ]
                });
            });
        </script>
    @endpush
@stop
