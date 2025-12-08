@extends('layouts.app')

@section('pageTitle', "{$project->name} - Globus User Account")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <h3 class="text-center">Update Globus Account</h3>
    <br/>

    <p>You don't have a Globus account configured.</p>
    <form method="post" id="globus" action="{{route('projects.globus.uploads.update_account', [$project])}}">

        @csrf
        <div class="mb-3">
            <label for="globus-user">Globus User Account</label>
            <input class="form-control" id="globus-user" name="globus_user" type="text"
                   value="{{$user->globus_user}}" placeholder="Globus User Account...">
        </div>
        <div class="float-end">
            <a class="action-link" href="#"
               onclick="document.getElementById('globus').submit()">
                Save
            </a>
        </div>
    </form>
    @include('common.errors')
@endsection
