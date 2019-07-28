@extends('layouts.project')

@section('pageTitle', 'Files')

@section('content')
    @component('components.card')
        @slot('header')
           Project {{$project->name}} directory {{$directory->name}}
        @endslot

        @slot('body')
            Files and directories here
        @endslot
    @endcomponent
@stop
