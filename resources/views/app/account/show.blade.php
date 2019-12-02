@extends('layouts.app')

@section('pageTitle', 'Projects')

@section('nav')
    @include('layouts.navs.app')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Account {{$user->name}}
        @endslot

        @slot('body')
            <h3 class="mb-3">User Name and Description</h3>
            <form method="post" id="user-details" action="{{route('accounts.update.details')}}">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{$user->name}}">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description"
                              name="description" type="text">{{$user->description}}</textarea>
                </div>
                <div class="float-right">
                    <a class="action-link" href="#"
                       onclick="document.getElementById('user-details').submit()">
                        Save
                    </a>
                </div>
            </form>

            <br>
            <hr>
            <h3 class="mb-3">Globus Account</h3>

            <form method="post" id="globus" action="{{route('accounts.update.globus')}}">
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

            <br>
            <hr>
            <h3 class="mb-3">Change Password</h3>

            <form method="post" id="change-password" action="{{route('accounts.update.password')}}">
                @csrf
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input class="form-control" id="current-password" name="password" type="password"
                           value="" placeholder="Current password...">
                </div>
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input class="form-control" id="new-password" name="new_password" type="password"
                           value="" placeholder="New password...">
                </div>
                <div class="form-group">
                    <label for="new-password2">Verify New Password</label>
                    <input class="form-control" id="new-password2" name="new_password2" type="password"
                           value="" placeholder="Re-enter new password...">
                </div>
                <div class="float-right">
                    <a class="action-link" href="#"
                       onclick="document.getElementById('change-password').submit()">
                        Change Password
                    </a>
                </div>
            </form>

            <br>
            <br>

            @include('common.errors')

            <form>
                <div class="float-right">
                    <button class="btn btn-default" onclick="window.history.back()">back</button>
                </div>
            </form>
        @endslot
    @endcomponent
@stop
