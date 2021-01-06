@extends('layouts.email')

@section('content')
    <p>
        Hi {{$user->name}},
    </p>
    <p>
        {{$removingUser->name}} has removed you from the project '{{$project->name}}'. If you believe this was
        a mistake please contact {{$addingUser->name}} at {{$addingUser->email}}.
    </p>
    <p>
        This project will no longer show up in your dashboard, or be accessible by you.
    </p>
    <p>
        Thank you,
        <br/>
        The Materials Commons Team
    </p>
@endsection