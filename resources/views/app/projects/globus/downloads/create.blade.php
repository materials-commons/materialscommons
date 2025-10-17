@extends('layouts.app')

@section('pageTitle', "{$project->name} - Create Globus Download")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Globus Download for project {{$project->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.globus.downloads.store', [$project])}}"
                  id="download-create">
                @csrf
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
                           placeholder="Name..." required>
                </div>
                {{--                    <div class="mb-3">--}}
                {{--                        <label for="description">Description</label>--}}
                {{--                        <textarea class="form-control" id="description" name="description" type="text"--}}
                {{--                                  placeholder="Description..."></textarea>--}}
                {{--                    </div>--}}
                <div class="float-end">
                    <a href="{{route('projects.globus.downloads.index', [$project])}}"
                       class="action-link danger me-3">
                        Cancel
                    </a>

                    <a class="action-link" href="#" onclick="document.getElementById('download-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
