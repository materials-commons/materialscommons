@extends('layouts.email')

@section('content')
    <p>
        Hi {{$etlRun->owner->name}},
    </p>
    <p>
        Your job loading the data from the spreadsheet
        <a href="{{route('projects.files.show', [$viewModel->project(), $file])}}">{{$file->name}}</a>
        has completed. We've included some details of
        this run, including a data dictionary and loading statistics. If you would like to explore this further you can
        click <a href="#">here</a>. Links to these results can also be be found on the home page for your
        <a href="{{route('projects.experiments.show', [$viewModel->project(), $viewModel->experiment()])}}">experiment</a>.
    </p>
    <h3>
        <a href="{{route('projects.experiments.show', [$viewModel->project(), $viewModel->experiment()])}}">
            Experiment {{$viewModel->experiment()->name}}
        </a>
    </h3>
    <br>
    <h5>Statistics</h5>
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

    <h5>Processes Data Dictionary</h5>
    <br>
    <table id="activities-dd" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Attribute</th>
            <th>Units</th>
            <th>Min</th>
            <th>Max</th>
            <th>Median</th>
            <th>Avg</th>
            <th>Mode</th>
            <th># Values</th>
        </tr>
        </thead>
        <tbody>
        @foreach($viewModel->activityAttributes() as $name => $attrs)
            <tr>
                <td>
                    <a href="{{$viewModel->activityAttributeRoute($name)}}">{{$name}}</a>
                </td>
                <td>{{$viewModel->units($attrs)}}</td>
                <td>{{$viewModel->min($attrs)}}</td>
                <td>{{$viewModel->max($attrs)}}</td>
                <td>{{$viewModel->median($attrs)}}</td>
                <td>{{$viewModel->average($attrs)}}</td>
                <td>{{$viewModel->mode($attrs)}}</td>
                <td>{{$attrs->count()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <br>
    <hr>
    <br>

    <h5>Samples Data Dictionary</h5>
    <br>
    <table id="entities-dd" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Attribute</th>
            <th>Units</th>
            <th>Min</th>
            <th>Max</th>
            <th>Median</th>
            <th>Avg</th>
            <th>Mode</th>
            <th># Values</th>
        </tr>
        </thead>
        <tbody>
        @foreach($viewModel->entityAttributes() as $name => $attrs)
            <tr>
                <td>
                    <a href="{{$viewModel->entityAttributeRoute($name)}}">{{$name}}</a>
                </td>
                <td>{{$viewModel->units($attrs)}}</td>
                <td>{{$viewModel->min($attrs)}}</td>
                <td>{{$viewModel->max($attrs)}}</td>
                <td>{{$viewModel->median($attrs)}}</td>
                <td>{{$viewModel->average($attrs)}}</td>
                <td>{{$viewModel->mode($attrs)}}</td>
                <td>{{$attrs->count()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection