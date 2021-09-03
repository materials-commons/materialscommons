@extends('layouts.email')

@section('content')
    <h2>Monthly statistics for {{$month}} {{$year}}</h2>

    <h4>User statistics</h4>
    <ul>
        <li>New users added for the month: {{number_format($userStats->numberOfJoinedOverLastMonth)}}</li>
        <li>Total users: {{number_format($userStats->totalNumberOfUsers)}}</li>
    </ul>

    <h4>Project statistics</h4>
    <ul>
        <li>New projects added for the
            month: {{number_format($projectStats->numberOfProjectsCreatedOverLastMonth)}}</li>
        <li>Total projects: {{number_format($projectStats->totalNumberOfProjects)}}</li>
    </ul>
    <h4>File statistics</h4>
    <ul>
        <li>Number of files uploaded for the month: {{number_format($fileStats->numberOfFilesAddedOverLastMonth)}}</li>
        <li>Total size of files uploaded for the month: {{formatBytes($fileStats->bytesAddedOverLastMonth)}}</li>
        <li>Total number of files: {{number_format($fileStats->totalNumberOfFiles)}}</li>
        <li>Total size of files: {{formatBytes($fileStats->totalBytes)}}</li>
    </ul>

    <h4>Dataset statistics</h4>
    <ul>
        <li>
            New datasets published this month: {{number_format($dsStats->numberOfDatasetsPublishedOverLastMonth)}}
            <ul>
                @foreach($dsStats->publishedDatasets as $ds)
                    <li>{{$ds->name}}</li>
                @endforeach
            </ul>
        </li>
        <li>Total number of published datasets: {{number_format($dsStats->totalNumberOfPublishedDatasets)}}</li>
    </ul>
@endsection