@extends('layouts.app')

@section('pageTitle', 'Markdown Editor - ' . $project->name)

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs')--}}
{{--    @include('projects.partials._breadcrumbs', [--}}
{{--        'breadcrumbs' => [--}}
{{--            ['text' => 'Projects', 'url' => route('projects.index')],--}}
{{--            ['text' => $project->name, 'url' => route('projects.show', ['project' => $project])],--}}
{{--            ['text' => 'Markdown Editor'],--}}
{{--        ]--}}
{{--    ])--}}
{{--@stop--}}

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Markdown Editor</h1>
                <p class="text-muted">
                    Edit markdown content and download it as a file.
                </p>
                <div class="card">
                    <div class="card-body">
                        <livewire:markdown-editor />
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
