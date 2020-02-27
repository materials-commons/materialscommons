@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    @component('components.card')
        @slot('header')
            Upload Files Step
        @endslot

        @slot('body')
            upload files
            <a href="{{route('public.publish.wizard.dataset_details')}}">goto dataset details</a>
        @endslot
    @endcomponent
@stop