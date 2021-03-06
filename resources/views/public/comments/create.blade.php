@extends('layouts.app')

@section('pageTitle', 'Create Dataset Comment')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Create Comment for Dataset {{$dataset->name}}
        @endslot

        @slot('body')
            <form method="post" action="{{route('public.datasets.comments.store', [$dataset])}}" id="comment-create">
                @csrf
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control" id="title" name="title" value="" type="text"
                           placeholder="Title..." required>
                </div>

                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea class="form-control" id="comment" name="body" type="text"
                              placeholder="Comment..." required></textarea>
                </div>
                <div class="float-right">
                    <a href="{{route('public.datasets.comments.index', [$dataset])}}" class="action-link danger mr-3">
                        Cancel
                    </a>
                    <a class="action-link" href="#"
                       onclick="document.getElementById('comment-create').submit()">
                        Create
                    </a>
                </div>
            </form>
        @endslot
    @endcomponent

    @include('common.errors')

@stop