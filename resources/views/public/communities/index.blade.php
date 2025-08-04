@extends('layouts.app')

@section('pageTitle', 'Public Data Community')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Data Communities
        @endslot

        @slot('body')
            <x-table-container>
                @include('public.communities._communities_table')
            </x-table-container>
        @endslot
    @endcomponent
@stop

