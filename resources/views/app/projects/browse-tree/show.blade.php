@extends('layouts.app')

@section('pageTitle', 'Browse Project Data')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <div class="container-fluid py-3">
        <x-browse-tree.page-header
            title="Browse Project Data"
            eyebrow="Project Data Browser"
            :subtitle="'Explore samples, computations, files, datasets, and experiments in '.$project->name.'.'"
        />

        <livewire:browse-tree.browse-tree
            :project="$project"
            default-scope="project"
        />
    </div>
@endsection
