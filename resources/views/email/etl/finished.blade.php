@extends('layouts.email')

@section('content')
    <h3>Experiment {{$viewModel->experiment()->name}}</h3>

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