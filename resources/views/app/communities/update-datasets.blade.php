@extends('layouts.app')

@section('pageTitle', 'Add Datasets To Community')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@section('content')
    <livewire:communities.dataset-selection
        :community="$community"
        :datasets="$datasets"
    />
@stop
