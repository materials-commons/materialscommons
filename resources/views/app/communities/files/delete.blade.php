@extends('layouts.app')

@section('pageTitle', 'Delete Community File')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete File: {{$file->name}} in Community {{$community->name}}
        @endslot

        @slot('body')
            <form method="post"
                  action="{{route('communities.files.destroy', [$community, $file])}}"
                  id="delete-file">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="{{$file->name}}" name="name" readonly>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description"
                              name="description" readonly>{{$file->description}}</textarea>
                </div>
                <div class="float-end">
                    <a href="{{route('communities.files.show', [$community, $file])}}" class="action-link danger me-3">
                        Cancel
                    </a>
                    <a class="action-link" onclick="document.getElementById('delete-file').submit()" href="#">
                        Delete
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
