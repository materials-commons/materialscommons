@extends('layouts.app')

@section('pageTitle', 'Show Community File')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            File: {{$file->name}} for Community: {{$community->name}}
            <a class="action-link float-right mr-4"
               href="{{route('communities.files.download', [$project, $file])}}">
                <i class="fas fa-download mr-2"></i>Download File
            </a>
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $file])
                <span class="ml-4">Mediatype: {{$file->mime_type}}</span>
            @endcomponent
            <hr>
            <br>
            @include('partials.files._display-file')
        @endslot
    @endcomponent
@stop