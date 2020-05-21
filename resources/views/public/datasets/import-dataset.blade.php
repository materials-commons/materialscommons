@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @include('partials.datasets._import-dataset', [
        'cancelImportRoute' => route('public.datasets.show', [$dataset]),
        'importDatasetRoute' => route('projects.datasets.import', [$project, $dataset])
])
@stop