@extends('layouts.app')

@section('pageTitle', 'Show Community File')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            File: {{$file->name}} for Community: {{$community->name}}
            <a class="action-link float-right"
               href="{{route('communities.files.download', [$community, $file])}}">
                <i class="fas fa-download mr-2"></i>Download
            </a>

            <a class="action-link float-right mr-4"
               href="{{route('communities.files.edit-file', [$community, $file])}}">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a class="action-link float-right mr-4" href="{{route('communities.files.delete', [$community, $file])}}">
                <i class="fas fa-trash mr-2"></i> Delete
            </a>
        @endslot

        @slot('body')
            @component('components.item-details', ['item' => $file])
                <span class="ml-4">Mediatype: {{$file->mime_type}}</span>
            @endcomponent
            <hr>
            <br>
            @include('partials.files._display-file', [
                'displayRoute' => route('communities.files.display', [$community, $file])
            ])
        @endslot
    @endcomponent
@stop