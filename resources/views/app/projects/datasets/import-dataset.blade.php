@extends('layouts.app')

@section('pageTitle', 'Import Dataset')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @include('partials.datasets._import-dataset', [
        'cancelImportRoute' => route('projects.datasets.show', [$project, $dataset]),
        'importDatasetRoute' => route('projects.datasets.import', [$project, $dataset])
])
@stop