@extends('layouts.email')

@section('content')
    <p>
        Dear {{$user->name}},
    </p>
    <p>
        You recently unpublished the dataset <a
                href="{{route('projects.datasets.show', [$dataset->project_id, $dataset])}}">{{$dataset->name}}</a>. The
        process of cleaning up the published files and data has completed and the dataset is now
        available if you wish to re-publish it.
    </p>
    <p>
        Thank you,
        <br/>
        The Materials Commons Team
    </p>
@endsection