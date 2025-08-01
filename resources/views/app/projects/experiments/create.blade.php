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
                    <div class="col-lg-4 col-sm-12 col-md-6">
                        <x-inner-card>
                            <x-slot:title>Study Details</x-slot:title>
                            <x-slot:body>
                                <div class="form-group mb-3">
                                    <label for="name">Name</label>
                                    <input class="form-control" id="name" name="name" type="text"
                                           value="{{old('name')}}"
                                           placeholder="Name...">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="summary">Summary</label>
                                    <input class="form-control" id="summary" name="summary" type="text"
                                           value="{{old('summary')}}"
                                           placeholder="Summary...">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" type="text"
                                              placeholder="Description...">{{old('description')}}</textarea>
                                </div>
                            </x-slot:body>
                        </x-inner-card>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-lg-8 col-sm-12 col-md-6">
                        <x-card card-body-classes="darker-card">
                            <x-slot:header>
                                Spreadsheet Import Options
                            </x-slot:header>
                            <x-slot:body>
                                <p class="mb-3">
                                    Import data from a spreadsheet to automatically set up your study.
                                    <a href="{{makeHelpUrl("reference/spreadsheets")}}" target="_blank" class="ms-2">
                                        <i class="fas fa-book-open"></i> View Format
                                    </a>
                                </p>

                                <div class="row mr-2 ml-1">
                                    <div class="col-md-12 white-box">
                                        <h5 class="text-centerx mt-3 mr-2 font-weight-bold">
                                            <i class="fas fa-file-excel me-2"></i> Excel File
                                        </h5>
                                        <hr/>
                                        <div class="mb-3">
                                            <label for="file_id">Select spreadsheet</label>
                                            <select name="file_id" class="selectpicker w-100"
                                                    data-live-search="true"
                                                    data-style="btn-light no-tt"
                                                    title="Select Spreadsheet">
                                                <option value=""></option>
                                                @foreach($excelFiles as $f)
                                                    <option data-tokens="{{$f->id}}"
                                                            value="{{$f->id}}">
                                                        {{$f->directory->path === "/" ? "" : $f->directory->path}}
                                                        /{{$f->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center my-3">
                                    <span class="px-3 text-muted">OR</span>
                                </div>

                                <div class="row mr-2 ml-1">
                                    {{-- Google Sheets Option --}}
                                    <div class="col-12 white-box">
                                        <h5 class="text-centerx mt-3 mr-2 font-weight-bold">
                                            <i class="fab fa-google me-2"></i> Google Sheets
                                        </h5>
                                        <hr>
                                        <div class="mb-3">
                                            <label for="url-id">New Sheet URL</label>
                                            <div class="input-group">
                                                <input class="form-control"
                                                       hx-get="{{route('projects.files.sheets.resolve-google-sheet', [$project])}}"
                                                       hx-target="#google-sheet-title"
                                                       hx-indicator=".htmx-indicator"
                                                       hx-trigger="keyup changed delay:500ms"
                                                       name="sheet_url" type="url"
                                                       placeholder="Paste URL here..."
                                                       id="url-id">
                                                <span class="htmx-indicator input-group-text"><i
                                                        class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                            <div id="google-sheet-title" class="small mt-1"></div>
                                        </div>

                                        @if($sheets->count() !== 0)
                                            <div class="mt-3">
                                                <label for="sheet_id">Or use existing sheet</label>
                                                <select name="sheet_id" class="selectpicker w-100"
                                                        data-live-search="true"
                                                        data-style="btn-light no-tt"
                                                        title="Select Google Sheet">
                                                    <option value=""></option>
                                                    @foreach($sheets as $s)
                                                        <option data-tokens="{{$s->id}}"
                                                                value="{{$s->id}}">{{$s->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="alert alert-info mt-3">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p>
                                                        <i class="fas fa-info-circle fa-lg me-3"></i>
                                                        <strong>Important:</strong>
                                                        Set sharing permissions to "Anyone with the link"
                                                        under General Access for the Google Sheet to be accessible.
                                                    </p>
                                                    <img src="{{asset('images/google-sheets-share.png')}}"
                                                         class="img-fluid mt-2 rounded shadow-sm"
                                                         style="max-width: 300px"
                                                         alt="Google Sheets sharing settings">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
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
