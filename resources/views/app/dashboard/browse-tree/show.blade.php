@extends('layouts.app')

@section('pageTitle', 'Browse All Research Data')

@section('content')
    <div class="container-fluid py-3">
        <x-browse-tree.page-header
            title="Browse All Research Data"
            eyebrow="Dashboard Data Browser"
            subtitle="Explore samples, computations, files, datasets, and experiments across your projects."
        />

        <livewire:browse-tree.browse-tree default-scope="all" />
    </div>
@endsection
