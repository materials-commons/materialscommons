@extends('layouts.app')

@section('pageTitle', "{$project->name} - Show Globus Upload")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Globus Transfer</h3>
    <br/>
    @if(!is_null($globusTransfer))
        <p>
            Your globus request has been set up. Click
            <a href="{{$globusTransfer->globus_url}}" target="_blank">here</a>
            to go to the Globus web interface.
        </p>
    @else
        <p>There is no active globus transfer.</p>
    @endif
@endsection
