@extends('layouts.app')

@section('pageTitle', 'Edit Community File')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Edit File {{$file->name}} In Community {{$community->name}}
        @endslot
        @slot('body')
            <form method="post" action="{{route('communities.files.update-file', [$community, $file])}}"
                  id="file-update">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" value="{{old('summary', $file->summary)}}"
                           name="summary">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..."
                              required>{{old('description', $file->description)}}</textarea>
                </div>

                <div class="float-end">
                    <a href="{{route('communities.files.show', [$community, $file])}}" class="action-link danger me-3">
                        Cancel
                    </a>
                    <a class="action-link" href="#"
                       onclick="document.getElementById('file-update').submit()">
                        Update
                    </a>
                </div>
            </form>
            <br>
            @include('partials.files._display-file', [
                'displayRoute' => route('communities.files.display', [$community, $file])
            ])
        @endslot
    @endcomponent
@stop
