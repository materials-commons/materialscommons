@extends('layouts.email')

@section('content')
    <p>
        The drop '{{$drop->name}}' in project '{{$drop->project->name}}' has been marked ready for import. The drop is
        no longer available for users
        to upload files into.
    </p>

    @if(!blank($userMessage))
        <p>
            Message from user:
        </p>
        <p>
            {{$userMessage}}
        </p>
    @endif

    <ul>
        <li>To start the import for this drop click <a
                href="{{route('projects.globus.drops.import', [$drop->project, $drop])}}">here</a>.
        </li>
        <li>To see the list of drops in the project click <a
                href="{{route('projects.globus.drops.index', [$drop->project])}}">here</a>.
        </li>
    </ul>
@endsection
