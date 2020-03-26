@extends('layouts.app')

@section('pageTitle', 'Create Experiment')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Experiment
        @endslot

        @slot('body')
            @include('app.projects.experiments._overview')
            <form method="post"
                  action="{{route('projects.experiments.store', [$project, 'show-overview' => request()->input('show-overview', false)])}}"
                  id="experiment-create">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="" placeholder="Name...">
                </div>
                <div class="form-group">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" name="summary" type="text" placeholder="Summary...">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text" value=""
                              placeholder="Description..."></textarea>
                </div>

                <hr>
                <div class="form-group">
                    <p style="margin-top: 8px">
                        Materials Commons can <b>optionally</b> import a spreadsheet to create your experiment. This
                        will set up the
                        processes, samples and files.
                    </p>
                    <p>
                        To see the format for the spreadsheet please read the
                        <a href="{{makeHelpUrl("reference/spreadsheets")}}" target="_blank">documentation</a>.
                    </p>

                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-10">
                            <a class="btn btn-info"
                               href="{{route('projects.experiments.upload-excel', [$project])}}">
                                <i class="fas fa-plus mr-2"></i>Add Spreadsheets To Import
                            </a>
                        </div>
                    </div>

                    @if ($excelFiles->count() !== 0)
                        <label for="file_id">Spreadsheet to import</label>
                        <select name="file_id" class="selectpicker col-lg-10" data-live-search="true"
                                title="Select Spreadsheet">
                            @foreach($excelFiles as $file)
                                @if ($file->directory->path === "/")
                                    <option data-tokens="{{$file->id}}" value="{{$file->id}}">/{{$file->name}}</option>
                                @else
                                    <option data-tokens="{{$file->id}}" value="{{$file->id}}">{{$file->directory->path}}
                                        /{{$file->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    @else
                        <span>There are no excel files in the project to build an experiment from.</span>
                    @endif
                </div>
                <hr>

                <input hidden id="project_id" name="project_id" value="{{$project->id}}">
                <div class="float-right">
                    <a href="{{route('projects.show', ['project' => $project->id])}}" class="action-link danger mr-3">
                        Cancel
                    </a>

                    <a class="action-link mr-3" href="#"
                       onclick="document.getElementById('experiment-create').submit()">
                        Create
                    </a>

                    <a class="action-link" href="#" onclick="createAndAddFiles()">
                        Create And Add Files
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection

@push('scripts')
    <script>
        function createAndAddFiles() {
            let actionRoute = "{!!route('projects.experiments.store', [$project, 'files-next' => true, 'show-overview' => request()->input('show-overview', false)])!!}";
            $("#experiment-create").attr('action', actionRoute);
            document.getElementById('experiment-create').submit();
        }
    </script>
@endpush
