@extends('layouts.app')

@section('pageTitle', "Drop not found")

@section('content')
    <p>
        {{$drop->owner->name}} will be notified that you've completed transferring files
        for this drop ({{$drop->name}}).
        The drop will no longer be available for uploads. Click <a href="/">here</a>
        to be taken to the Materials Commons home page.
    </p>
@stop
