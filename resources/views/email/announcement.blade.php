@extends('layouts.email')

@section('content')
    <p>
        Hello {{$user->name}},
    </p>
    <p>
        We are excited to announce that a completely new version of Materials Commons is
        available. This is a ground up rewrite of the site that adds many ease of use
        enhancements. We also completely reworked how you construct workflows and interact
        with the site.
    </p>

    {{--    <img src="{{$message->embedData(file_get_contents(storage_path('email/project-home.png')), 'project-home.png')}}">--}}
    <div class="col-6">
        {{--        <img width="400" height="300"--}}
        <img class="img-fluid"
             src="data:image/png;base64,{{base64_encode(file_get_contents(storage_path('email/project-home.png')))}}">
    </div>
    <p>
        Hello 2
    </p>
@endsection