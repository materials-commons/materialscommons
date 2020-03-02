@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Published Authors
        @endslot

        @slot('body')
            <ul>
                @foreach($authors as $author => $count)
                    <li>
                        <a href="{{route('public.authors.search', ['search' => $author])}}">{{$author}}</a>
                        has {{$count}} @choice('dataset|datasets2', $count) published.
                    </li>
                @endforeach
            </ul>
        @endslot
    @endcomponent
@stop
