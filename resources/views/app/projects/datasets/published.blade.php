@extends('layouts.app')

@section('pageTitle', 'Show Dataset')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Published dataset: {{$dataset->name}}
        </x-slot>
        <x-slot name="body">
            <p>
                You have successfully published the dataset '{{$dataset->name}}'. Your dataset will now show
                up in the list of published datasets. Below is a list of tasks we are performing in the background.
                Once they are completed you will receive an email.
            </p>
            <ul>
                <li>If the total size of your dataset is less than 200GB a zipfile will be created containing all the
                    files.
                </li>
                <li>Globus is being setup allowing users to download your files.</li>
                <li>Copies of all your data, including files, samples and processes (if you have any) are being made.
                    Once
                    this is done you can make changes to your project without affecting your published data.
                </li>
            </ul>
            <a class="btn btn-success" href="{{route('projects.datasets.index', [$project])}}">Done</a>
        </x-slot>
    </x-card>
@stop