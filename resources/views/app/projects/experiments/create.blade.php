@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    {{--            <x-card-container>--}}
    @include('app.projects.experiments._overview')

    <form method="post"
          action="{{route('projects.experiments.store', [$project, 'show-overview' => request()->input('show-overview', false)])}}"
          id="experiment-create">
        @csrf
        <div class="row">
            {{-- Left Column --}}
            <div class="col-lg-6 col-sm-12 col-md-6">
                @include('app.projects.experiments._create-details')
            </div>

            {{-- Right Column --}}
            <div class="col-lg-6 col-sm-12 col-md-6">
                <h3>Spreadsheet Import Options</h3>
                <p class="mb-3">
                    Import data from a spreadsheet to automatically set up your study.
                    <a href="/mcdocs2/guides/spreadsheets.html" target="_blank" class="ms-2">
                        <i class="fas fa-book-open"></i> View Format
                    </a>
                </p>

                <br/>
                @include('app.projects.experiments._create-import-excel')

                <div class="text-center my-3">
                    <span class="px-3 text-muted">OR</span>
                </div>

                @include('app.projects.experiments._create-import-google-sheet')

            </div>
        </div>
        <input hidden id="project_id" name="project_id" value="{{$project->id}}">
    </form>


    @include('common.errors')
@endsection

@push('styles')
    <style>
        .bootstrap-select.show .dropdown-menu {
            max-width: 100%;
            width: 100%;
        }

    </style>
@endpush
