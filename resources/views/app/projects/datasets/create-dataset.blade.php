@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Dataset")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot:header>
            Create Dataset
        </x-slot:header>
        <x-slot:body>
            <livewire:projects.datasets.crud.create-dataset :project="$project" :dataset="$dataset"/>
        </x-slot:body>
    </x-card>
@stop