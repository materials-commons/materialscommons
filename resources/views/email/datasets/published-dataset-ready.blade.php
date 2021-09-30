@extends('layouts.email')

@section('content')
    <p>
        Dear {{$user->name}},
    </p>
    <p>
        You recently published the dataset <a
                href="{{route('projects.datasets.show', [$dataset->project_id, $dataset])}}">{{$dataset->name}}</a>.
        The files are now available for download using Globus. A zipfile is also being built.
    </p>
    <p>
        Thank you,
        <br/>
        The Materials Commons Team
    </p>
@endsection