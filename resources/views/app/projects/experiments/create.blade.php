@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <div class="mc-study-page">
        <div class="mc-study-page-heading d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h2 class="h5 mb-1">Create Study</h2>
                <div class="text-muted small">
                    Create a new study and optionally load data from a spreadsheet or Google Sheet.
                </div>
            </div>

            <span class="badge text-bg-light border">
                <i class="fas fa-flask me-1"></i>
                New Study
            </span>
        </div>

        @include('app.projects.experiments._overview')

        <x-projects.experiments.create.help-panel />

        <form method="post"
              action="{{ route('projects.experiments.store', [$project, 'show-overview' => request()->input('show-overview', false)]) }}"
              id="experiment-create">
            @csrf

            <div class="row g-4">
                <div class="col-12 col-xl-5">
                    <x-projects.experiments.create.details-panel :project="$project" />
                </div>

                <div class="col-12 col-xl-7">
                    <x-projects.experiments.create.import-panel
                        :project="$project"
                        :excel-files="$excelFiles"
                        :sheets="$sheets"
                    />
                </div>
            </div>

            <input hidden id="project_id" name="project_id" value="{{ $project->id }}">
        </form>

        @include('common.errors')
    </div>
@endsection
