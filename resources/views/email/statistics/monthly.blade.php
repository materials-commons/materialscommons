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

    <h4>Published And Top Projects</h4>
    <ul>
        @foreach($projectStats->topProjects as $project)
            <li>
                Project: {{$project->name}}
                <ul>
                    <li>ID: {{$project->id}}</li>
                    <li>Owner: {{$project->owner->name}}</li>
                    <li>Size: {{formatBytes($project->size)}}</li>
                    <li>
                        Datasets:
                        <ul>
                            @forelse($project->publishedDatasets as $ds)
                                <li>
                                    <a href="{{route('public.datasets.show', [$ds])}}">{{$ds->name}}</a>
                                    <ul>
                                        <li>ID: {{$ds->id}}</li>
                                        <li>Zipfile Size: {{formatBytes($ds->zipfile_size)}}</li>
                                    </ul>
                                </li>
                            @empty
                                <li>No published datasets in project</li>
                            @endforelse
                        </ul>
                    </li>
                </ul>
            </li>
        @endforeach
    </ul>
@endsection