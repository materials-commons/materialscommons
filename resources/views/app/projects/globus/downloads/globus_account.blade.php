@extends('layouts.app')

@section('pageTitle', "{$project->name} - Globus User Account")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Update Globus Account
        @endslot

        @slot('body')
            <p>You don't have a Globus account configured.</p>
            <form method="post" id="globus" action="{{route('projects.globus.downloads.update_account', [$project])}}">

                @csrf
                <div class="form-group">
                    <label for="globus-user">Globus User Account</label>
                    <input class="form-control" id="globus-user" name="globus_user" type="text"
                           value="{{$user->globus_user}}" placeholder="Globus User Account...">
                </div>
                <div class="float-right">
                    <a class="action-link" href="#"
                       onclick="document.getElementById('globus').submit()">
                        Save
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent
    @include('common.errors')
@endsection
