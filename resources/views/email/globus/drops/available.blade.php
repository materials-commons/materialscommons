@extends('layouts.email')

@section('content')
    <p>
        {{$drop->owner->name}} ({{$drop->owner->email}}) has shared the drop "{{$drop->name}}"
        with you on Materials Commons. You can transfer files to it using Globus.
    </p>

    @if(!blank($drop->message))
        <p>{{$drop->message}}</p>
    @endif

    <p>
        To transfer files click <a href="{{$drop->globus_url}}">here</a>.
    </p>

    <p>
        To notify {{$drop->owner->name}} that the file transfer is finished click <a
            href="{{route('globus-drop.mark-done', [$drop->uuid])}}">here</a>.
    </p>

@endsection
