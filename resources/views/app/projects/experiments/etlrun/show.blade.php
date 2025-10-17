@extends('layouts.app')

@section('pageTitle', "{$project->name} - Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.experiments.index', $project))

@section('content')
    <x-card>
        <x-slot name="header">
            @if(isset($etlRun->files[0]))
                Show Spreadsheet load for {{$etlRun->files[0]->name}} run on: {{$etlRun->created_at}}
            @else
                Show Spreadsheet load for Google Spreadsheet run on: {{$etlRun->created_at}}
            @endif
            <a class="action-link float-end" style="cursor: pointer"
               hx-get="{{route('projects.experiments.etl_run.show-log', [$project, $experiment, $etlRun])}}"
               onclick="clearSearchInputOnReload()"
               hx-target="#etl-log"
               hx-swap="innerHTML scroll:bottom">
                <i class="fas fa-sync-alt mr-2"></i>Refresh Log
            </a>
        </x-slot>
        <x-slot name="body">
            <h3>
                Search Log
                <span class="htmx-indicator">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </h3>

            <input class="form-control col-6 mb-4" type="text" id="search-input"
                   name="search" placeholder="Search log..."
                   hx-get="{{route('projects.experiments.etl_run.search', [$project, $experiment, $etlRun])}}"
                   hx-target="#etl-log"
                   hx-indicator=".htmx-indicator"
                   hx-trigger="keyup changed delay:500ms">
            <div hx-get="{{route('projects.experiments.etl_run.show-log', [$project, $experiment, $etlRun])}}"
                 hx-trigger="load" hx-target="#etl-log">
                <div id="etl-log" style="overflow-y: auto; overflow-x: auto; height: 70vh"></div>
            </div>
        </x-slot>
    </x-card>
@endsection

@push('scripts')
    <script>
        function clearSearchInputOnReload() {
            document.getElementById('search-input').value = '';
        }
    </script>
@endpush
