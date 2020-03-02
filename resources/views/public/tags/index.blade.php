@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Published Data Tags
        @endslot

        @slot('body')
            <ul>
                @foreach($tags as $tag => $count)
                    <li>
                        <a href="{{route('public.tags.search', ['tag' => $tag])}}">{{$tag}}</a>
                        has {{$count}} @choice('dataset|datasets', $count).
                    </li>
                @endforeach
            </ul>
        @endslot
    @endcomponent
@stop
