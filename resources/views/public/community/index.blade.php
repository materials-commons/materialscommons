@extends('layouts.public')

@section('pageTitle', 'Public Data Community')

@section('content')
    @component('components.card')
        @slot('header')
            Public Data Communities
        @endslot

        @slot('body')
            <div class="container-fluid content-row">
                <div class="row">
                    <div class="col-lg-4 d-flex align-items-stretch">
                        <div class="card h-100x">
                            … content card …
                            <p>Stuff</p>
                        </div>

                        <div class="card h-100x">
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
