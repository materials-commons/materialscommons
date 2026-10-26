@extends('layouts.email')

@section('content')
    <p>
        {{$drop->owner->name}} ({{$drop->owner->email}}) has shared the drop "{{$drop->name}}"
        with you on Materials Commons. You can transfer files to it using Globus.
    </p>

    <p>
        To transfer files click <a href="{{$drop->globus_url}}">here</a>.
    </p>

    <p>
        To notify Brian that the file transfer is finished click <a href="">here</a>.
    </p>

@endsection
