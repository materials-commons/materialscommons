@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Workflow Step
        @endslot

        @slot('body')
            <p>
                Adding a workflow is an optional step. A workflow is a nice way to display the steps used to gather
                your data and create results. If you decide not to add a workflow you can still come back after you
                publish your workflow and add it at a later time.
            </p>
            @include('partials.workflows.create', [
                'saveButtonName' => "Save And Next",
                'storeRoute' => route('projects.workflows.store', [$project]),
                'cancelButtonName' => "Skip",
                'cancelRoute' => route('projects.workflows.index', [$project]),
            ])
        @endslot
    @endcomponent
@stop

@push('scripts')
    <script>
        $(document).ready(() => {
            drawWorkflow();
        });
    </script>
@endpush