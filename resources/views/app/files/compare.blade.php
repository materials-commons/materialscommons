@extends('layouts.app')

@section('pageTitle', 'Compare Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')

    <x-card>
        <x-slot name="header">
            Comparing versions of file {{$file1->name}}
        </x-slot>
        <x-slot name="body">
            <div class="row">
                <div class="col-6">
                    <h4>{{$file1->name}} uploaded on {{$file1->created_at}}</h4>
                    <x-show-standard-details :item="$file1">
                        <span class="ms-3 fs-10 grey-5">Mediatype: {{$file1->mime_type}}</span>
                        <span class="ms-3 fs-10 grey-5">Size: {{$file1->toHumanBytes()}}</span>
                    </x-show-standard-details>
                    <hr>
                    <br>
                    <x-display-file display-route="{{route('projects.files.display', [$project, $file1])}}"
                                    :file="$file1"/>
                </div>
                <div class="col-6">
                    <h4>{{$file2->name}} uploaded on {{$file2->created_at}}</h4>
                    <x-show-standard-details :item="$file2">
                        <span class="ms-3 fs-10 grey-5">Mediatype: {{$file2->mime_type}}</span>
                        <span class="ms-3 fs-10 grey-5">Size: {{$file2->toHumanBytes()}}</span>
                    </x-show-standard-details>
                    <hr>
                    <br>
                    <x-display-file display-route="{{route('projects.files.display', [$project, $file2])}}"
                                    :file="$file2"/>
                </div>
            </div>

        </x-slot>
    </x-card>
@endsection