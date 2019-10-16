@extends('layouts.app')

@section('pageTitle', 'Show Dataset')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.datasets.show', $project, $dataset))

@section('content')
    @component('components.card')
        @slot('header')
            Dataset: {{$dataset->name}}
            <a class="float-right action-link"
               href="{{route('projects.datasets.edit', [$project, $dataset])}}">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>

            <a data-toggle="modal" class="float-right action-link mr-4"
               href="#project-delete-{{$project->id}}">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </a>
        @endslot

        @slot('body')
            <div class="ml-5">
                <dl class="row">
                    <dt class="col-sm-2">Name</dt>
                    <dd class="col-sm-10">{{$dataset->name}}</dd>
                    <dt class="col-sm-2">DOI</dt>
                    <dd class="col-sm-10">{{$dataset->doi}}</dd>
                    <dt class="col-sm-2">Funding</dt>
                    <dd class="col-sm-10">{{$dataset->funding}}</dd>
                    <dt class="col-sm-2">License</dt>
                    <dd class="col-sm-10">{{$dataset->license}}</dd>
                    <dt class="col-sm-2">Authors</dt>
                    <dd class="col-sm-10">{{$dataset->authors}}</dd>
                </dl>
            </div>
            <div class="row ml-5">
                <h5>Description</h5>
            </div>
            <div class="row ml-5">
                <p>{{$dataset->description}}</p>
            </div>
            <br>
        @endslot
    @endcomponent

    @component('components.card')
        @slot('header')
            {{$directory->path}}
        @endslot

        @slot('body')
            <table id="files" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Selected</th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>
                            @if ($file->mime_type === 'directory')
                                <a href="{{route('projects.datasets.show.next', [$project, $dataset, $file])}}">
                                    <i class="fa-fw fas mr-2 fa-folder"></i> {{$file->name}}
                                </a>
                            @else
                                <a href="{{route('projects.files.show', [$project, $file])}}">
                                    <i class="fa-fw fas mr-2 fa-file"></i>{{$file->name}}
                                </a>
                            @endif
                        </td>
                        <td>{{$file->mime_type}}</td>
                        @if ($file->mime_type === 'directory')
                            <td>N/A</td>
                        @else
                            <td>{{$file->toHumanBytes()}}</td>
                        @endif
                        <td>
                            <div class="form-group form-check-inline">
                                <input type="checkbox" class="form-check-input" id="{{$file->uuid}}"
                                       {{$file->selected ? 'checked' : ''}} readonly onclick="return false;">
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(function () {
                $(document).ready(() => {
                    $('#files').DataTable({
                        stateSave: true,
                    });
                });
            });
        </script>
    @endpush

@stop