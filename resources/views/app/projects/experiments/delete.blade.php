@extends('layouts.app')

@section('pageTitle', "{$project->name} - Delete Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete Study: {{$experiment->name}}
        @endslot

        @slot('body')
            @if($publishedDatasets->isEmpty())
                <form method="post"
                      action="{{route('projects.experiments.destroy', ['project' => $project, 'experiment' => $experiment])}}"
                      id="delete-experiment">
                    @csrf
                    @method('delete')

                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input class="form-control" id="name" value="{{$experiment->name}}" name="name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description"
                                  name="description" readonly>{{$experiment->description}}</textarea>
                    </div>
                    <input hidden name="project_id" value="{{$project->id}}">
                    <div class="float-end">
                        <a href="{{route('projects.show', ['project' => $project])}}" class="action-link danger me-3">
                            Cancel
                        </a>
                        <a class="action-link" onclick="document.getElementById('delete-experiment').submit()" href="#">
                            Delete
                        </a>
                    </div>
                </form>

                @if($unpublishedDatasets->isNotEmpty())
                    <br>
                    <br>
                    <p>
                        The following unpublished datasets share samples/computations with this study. If you choose to
                        delete
                        the study then the shared samples will need to be manually added back to the datasets.
                    </p>
                    <h5>Affected Unpublished Datasets</h5>
                    <ul>
                        @foreach($unpublishedDatasets as $ds)
                            <li>
                                <a href="{{route('projects.datasets.show', [$project, $ds])}}">{{$ds->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @else
                <br>
                <p>
                    This study cannot be deleted. It contains published datasets that would be affected
                    by the delete.
                </p>
                <h5>Affected Published Datasets</h5>
                <ul>
                    @foreach($publishedDatasets as $ds)
                        <li>
                            <a href="{{route('projects.datasets.show', [$project, $ds])}}">{{$ds->name}}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
