@extends('layouts.app')

@section('pageTitle', 'Create Community')

@section('nav')
    @include('layouts.navs.app')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Community
        @endslot

        @slot('body')
            <form method="post" action="{{route('communities.store')}}" id="community-create">
                @csrf
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" value="{{old('name')}}" type="text"
                           placeholder="Name..." required>
                </div>

                <div class="mb-3">
                    <label for="summary">Summary</label>
                    <input class="form-control" id="summary" name="summary" type="text" value="{{old('summary')}}"
                           placeholder="Summary...">
                </div>

                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              placeholder="Description..." required>{{old('description')}}</textarea>
                </div>
                <div class="mb-3 form-check-inline">
                    <label class="form-check-label me-2" for="public">Public?</label>
                    <input type="hidden" name="public" value="0"/>
                    <input type="checkbox" value="1" class="form-check-input" id="public"
                           name="public" {{old('public') ? 'checked' : ''}}>
                </div>
                <div class="float-end">
                    <a href="{{route('communities.index')}}" class="action-link danger me-3">
                        Cancel
                    </a>
                    <a class="action-link" href="#"
                       onclick="document.getElementById('community-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')

@stop
