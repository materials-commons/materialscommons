@extends('layouts.app')

@section('pageTitle', 'Delete')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Delete {{$item->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{$destroyRoute}}">
                @csrf
                @method('delete')
                <div class="mb-3">
                    <p>Delete {{$item->name}}?</p>
                </div>
                <div class="float-right">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" onclick="window.history.go(-1)" class="btn btn-success">Cancel</button>
                </div>
            </form>
        @endslot
    @endcomponent
@endsection
