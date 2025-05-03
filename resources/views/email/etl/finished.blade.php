@extends('layouts.email')

@section('content')
    <p>
        Hi {{$etlRun->owner->name}},
    </p>
    @if(is_null($file))
        <p>
            Your job loading the data from the <a href="{{$sheetUrl}}">Google sheet</a> has completed.
        </p>
    @else
        <p>
            Your job loading the data from the spreadsheet
            <a href="{{route('projects.files.show', [$viewModel->project(), $file])}}">{{$file->name}}</a>
            has completed.
        </p>
    @endif
    <h3>
        Study:
        <a href="{{route('projects.experiments.show', [$viewModel->project(), $viewModel->experiment()])}}">
            {{$viewModel->experiment()->name}}
        </a>
    </h3>
    <br>
    <h5>Overview</h5>
    <table class="table" style="width:100%">
        <thead>
        <tr>
            <th># Processes</th>
            <th># Samples</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{$etlRun->n_activities}}</td>
            <td>{{$etlRun->n_entities}}</td>
        </tr>
        </tbody>
    </table>
@endsection