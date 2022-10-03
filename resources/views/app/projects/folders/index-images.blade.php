@extends('layouts.app')

@section('pageTitle', 'Images')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            {{$folder->path}}
        </x-slot>
        <x-slot name="body">
            <div class="container">
                @foreach($images as $image)
                    <div class="row">
                        <div class="col-lg-12 mt-2">
                            <hr/>
                            <div class="text-center">
                                <span>{{$image->name}}</span>
                                <br>
                                <br>
                                <img src="{{route('projects.files.display', [$project, $image])}}" class="img-fluid">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-slot>
    </x-card>
@stop