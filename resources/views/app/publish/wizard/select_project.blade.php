@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    @component('components.card')
        @slot('header')
            Select Project Step
        @endslot

        @slot('body')
            @include('partials.publish.wizard._select_project_step', [
                'createProjectRoute' => '',
                'cancelRoute' => ''
            ])

            <a href="{{route('public.publish.wizard.upload_files')}}">goto upload files</a>
        @endslot
    @endcomponent
@stop