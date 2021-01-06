@extends('layouts.email')

@section('content')
    <p>
        Hi {{$user->name}},
    </p>
    <p>
        {{$addingUser->name}} has added you to the project '{{$project->name}}'. If you believe this was
        a mistake please contact {{$addingUser->name}} at {{$addingUser->email}}.
    </p>
    <p>
        This project will now show up in your dashboard. You may go directly to the project by clicking
        <a href="{{route('projects.show', [$project])}}">here</a>.
    </p>
    <p>
        Thank you,
        <br/>
        The Materials Commons Team
    </p>
@endsection