@extends('layouts.app')

@section('pageTitle', 'Create Globus Upload')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Globus Upload for project {{$project->name}}
        @endslot

        @slot('body')
            @if(isset($user->globus_user))
                <form method="post" action="{{route('projects.globus.uploads.store', [$project])}}"
                      id="upload-create">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" id="name" name="name" type="text"
                               placeholder="Name..." required>
                    </div>
                    {{--                    <div class="form-group">--}}
                    {{--                        <label for="description">Description</label>--}}
                    {{--                        <textarea class="form-control" id="description" name="description" type="text"--}}
                    {{--                                  placeholder="Description..."></textarea>--}}
                    {{--                    </div>--}}
                    <div class="float-right">
                        <a href="{{route('projects.show', [$project])}}" class="action-link danger mr-3">
                            Cancel
                        </a>

                        <a class="action-link" href="#" onclick="document.getElementById('upload-create').submit()">
                            Create
                        </a>
                    </div>
                </form>
            @else
                <p>You don't have a Globus account configured. To correct this click
                    <a href="{{route('accounts.show')}}">here</a> to go to the accounts page and set your globus
                    account.
                </p>
            @endif
        @endslot
    @endcomponent

    @include('common.errors')
@endsection
