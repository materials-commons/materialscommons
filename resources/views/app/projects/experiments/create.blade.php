@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Study")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot:header>Create Study</x-slot:header>
        <x-slot:body>
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
                        <x-card card-body-classes="darker-card">
                            <x-slot:header>
                                Spreadsheet Import Options
                            </x-slot:header>
                            <x-slot:body>
                                <p class="mb-3">
                                    Import data from a spreadsheet to automatically set up your study.
                                    <a href="/mcdocs2/guides/spreadsheets.html" target="_blank" class="ms-2">
                                        <i class="fas fa-book-open"></i> View Format
                                    </a>
                                </p>

                                @include('app.projects.experiments._create-import-excel')

                                <div class="text-center my-3">
                                    <span class="px-3 text-muted">OR</span>
                                </div>

                                @include('app.projects.experiments._create-import-google-sheet')

                            </x-slot:body>
                        </x-card>
                    </div>
                </div>
                <input hidden id="project_id" name="project_id" value="{{$project->id}}">

                <div class="float-right">
                    <a href="{{route('projects.show', ['project' => $project->id])}}"
                       class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link mr-3" href="#"
                       onclick="document.getElementById('experiment-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        </x-slot:body>
    </x-card>

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
