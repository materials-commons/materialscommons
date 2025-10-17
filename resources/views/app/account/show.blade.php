@extends('layouts.app')

@section('pageTitle', 'Account Details')

@section('nav')
    @include('layouts.navs.app')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.index'))--}}

@section('content')
    @component('components.card')
        @slot('header')
            Account Details for {{$user->name}}
        @endslot

        @slot('body')
            <x-card-container>
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
                    <div class="form-group">
                        <label for="affiliations">Affiliations</label>
                        <textarea class="form-control" id="affiliations"
                                  name="affiliations" type="text">{{$user->affiliations}}</textarea>
                    </div>
                    <div class="float-end">
                        <a class="action-link" href="#"
                           onclick="document.getElementById('user-details').submit()">
                            Save
                        </a>
                    </div>
                </form>

                @if(isInBeta('google-sheets'))
                    <br/>
                    <hr/>
                    <br/>
                    <h4>Google Sheets Connection Status</h4>
                    <p>
                        @if ($user->hasGoogleToken())
                            <span class="text-success">✓ Connected to Google Sheets</span>
                            <a href="{{ route('google-sheets.authorize') }}"
                               class="btn btn-sm btn-outline-primary ms-2">Reconnect</a>
                        @else
                            <span class="text-danger">✗ Not connected to Google Sheets</span>
                            <a href="{{ route('google-sheets.authorize') }}" class="btn btn-primary ms-2">Connect to
                                Google
                                Sheets</a>
                        @endif
                    </p>
                @endif
                <br>
                <hr>
                <br/>
                <div x-data="apiToken">
                    <h3 class="mb-3">API Token</h3>
                    <a href="#" @click.prevent="toggleAPIToken()" id="apitokenlink">Show API Token</a>
                    <div class="form-group" id="apitoken" style="display:none">
                        <label for="apitokeninput">API Token</label>
                        <input id="apitokeninput" value="{{$user->api_token}}" class="form-control" readonly>
                    </div>
                    <a href="{{route('accounts.api-token.regenerate')}}"
                       class="ms-5 btn btn-sm btn-outline-danger ms-2">Regenerate API Token</a>
                    <span class="text-muted">(This will invalidate any existing clients using the old token)</span>
                </div>
                <br>
                <hr>
                <br/>
                <h3 class="mb-3">Globus Account</h3>

                <form method="post" id="globus" action="{{route('accounts.update.globus')}}">
                    @csrf
                    <div class="form-group">
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

                <br>
                <hr>
                <br/>
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
                    <div class="float-end">
                        <a class="action-link" href="#"
                           onclick="document.getElementById('change-password').submit()">
                            Change Password
                        </a>
                    </div>
                </form>

                <br>
                <hr>
                <br/>
                <h3 class="mb-3">Change Email Address</h3>
                <form method="post" id="change-email" action="{{route('accounts.update.email')}}">
                    @csrf
                    <div class="form-group">
                        <label for="current-email">Current Email</label>
                        <input class="form-control" id="current-email" name="email" type="email"
                               value="{{$user->email}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="new-email">New Email</label>
                        <input class="form-control" id="new-email" name="new_email" type="email"
                               value="" placeholder="New Email...">
                    </div>
                    <div class="form-group">
                        <label for="new-email2">Verify New Email</label>
                        <input class="form-control" id="new-email2" name="new_email2" type="email"
                               value="" placeholder="Re-enter new Email...">
                    </div>
                    <div class="float-end">
                        <a class="action-link" href="#"
                           onclick="document.getElementById('change-email').submit()">
                            Change Email
                        </a>
                    </div>
                </form>

                <br>
                <br>

                @include('common.errors')

                <form>
                    <div class="float-end">
                        <button class="btn btn-default" onclick="window.history.back()">back</button>
                    </div>
                </form>
            </x-card-container>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            mcutil.onAlpineInit("apiToken", () => {
                return {
                    showingAPIToken: false,
                    toggleAPIToken() {
                        document.getElementById('apitoken').style.display = this.showingAPIToken ? "none" : "block";
                        document.getElementById('apitokenlink').innerHTML = this.showingAPIToken ? "Show API Token" : "Hide API Token";
                        this.showingAPIToken = !this.showingAPIToken;
                    }
                }
            });
        </script>
    @endpush
@stop
