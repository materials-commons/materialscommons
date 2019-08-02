@extends('layouts.app')

@section('pageTitle', 'Public Data Community')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Public Data Communities
        @endslot

        @slot('body')
            <div class="container-fluid content-row">
                <div class="row">
                    <div class="col-lg-4 d-flex align-items-stretch">
                        <div class="card community-card">
                            … content card …
                            <p>Stuff</p>
                        </div>

                        <div class="card community-card">
                            … content card …
                            <p>Stuff</p>
                            <p>stuff 2</p>
                        </div>
                    </div>
                </div>
            </div>
        @endslot
    @endcomponent
@stop
