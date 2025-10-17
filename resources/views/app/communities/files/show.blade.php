@extends('layouts.app')

@section('pageTitle', 'Show Community File')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            File: {{$file->name}} for Community: {{$community->name}}
            <a class="action-link float-end"
               href="{{route('communities.files.download', [$community, $file])}}">
                <i class="fas fa-download me-2"></i>Download
            </a>

            <a class="action-link float-end me-4"
               href="{{route('communities.files.edit-file', [$community, $file])}}">
                <i class="fas fa-edit me-2"></i>Edit
            </a>

            <a class="action-link float-end me-4" href="{{route('communities.files.delete', [$community, $file])}}">
                <i class="fas fa-trash me-2"></i> Delete
            </a>
        @endslot

        @slot('body')
            <x-show-standard-details :item="$file">
                <span class="ms-3 fs-10 grey-5">Mediatype: {{$file->mime_type}}</span>
                <span class="ms-3 fs-10 grey-5">Size: {{$file->toHumanBytes()}}</span>
            </x-show-standard-details>
            <hr>
            <br>
            @include('partials.files._display-file', [
                'displayRoute' => route('communities.files.display', [$community, $file])
            ])
        @endslot
    @endcomponent
@stop
