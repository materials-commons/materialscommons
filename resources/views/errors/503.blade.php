@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message')
    Materials Commons is down for maintenance
    @if(file_exists(config('server.down_file')))
        {{file_get_contents(config('server.down_file'))}}
    @endif
@endsection
