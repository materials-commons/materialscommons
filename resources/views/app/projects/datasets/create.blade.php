@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Dataset")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="eyebrow mb-1">
                New Dataset
            </div>
            <h4 class="mb-0 fw-bold">{{ $project->name }}</h4>
        </div>
        <a href="{{ route('projects.datasets.index', ['project' => $project->id]) }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
    </div>

    {{-- Tab nav — only Details is active until the dataset is saved --}}
    <ul class="nav nav-tabs mb-0" role="tablist">
        <li class="nav-item">
            <span class="nav-link active d-flex align-items-center gap-2" style="cursor:default;">
                <i class="fas fa-circle text-muted small"></i>
                Details
            </span>
        </li>
        <li class="nav-item">
            <span class="nav-link disabled d-flex align-items-center gap-2">
                <i class="fas fa-circle text-muted small"></i>
                Files
            </span>
        </li>
        <li class="nav-item">
            <span class="nav-link disabled d-flex align-items-center gap-2">
                <i class="fas fa-circle text-muted small"></i>
                Samples
                <span class="badge text-bg-light border fw-normal">optional</span>
            </span>
        </li>
        <li class="nav-item">
            <span class="nav-link disabled d-flex align-items-center gap-2">
                <i class="fas fa-circle text-muted small"></i>
                Workflows
                <span class="badge text-bg-light border fw-normal">optional</span>
            </span>
        </li>
    </ul>

    <div class="border-start border-end border-bottom rounded-bottom px-3 py-2 mb-4 bg-light">
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Save the dataset details first to continue to Files, Samples, and Workflows.
        </small>
    </div>

    @include('app.projects.datasets._create')
@stop
