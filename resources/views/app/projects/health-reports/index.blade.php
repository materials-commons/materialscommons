@extends('layouts.app')

@section('pageTitle', "{$project->name} - Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.experiments.show', $project, $experiment))--}}

@section('content')

    <h3 class="text-center">Health Reports</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Health Report</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reportDates as $report)
            <tr>
                <td><a href="{{route('projects.health-reports.show', [$project, $report])}}">{{$report}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
