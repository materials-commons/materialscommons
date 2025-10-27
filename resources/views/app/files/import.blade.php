@extends('layouts.app')

@section('pageTitle', 'Create Study From Spreadsheet')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Study From Spreadsheet: {{$file->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.files.create-experiment', [$project, $file])}}"
                  id="import-spreadsheet">
                @csrf
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value=""
                           placeholder="Study Name...">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text" value=""
                              placeholder="Description..."></textarea>
                </div>
                <div class="float-end">
                    <a href="{{URL::previous()}}" class="action-link danger me-3">
                        Cancel
                    </a>

                    <a class="action-link" href="#" onclick="document.getElementById('import-spreadsheet').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
