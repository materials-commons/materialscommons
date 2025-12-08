@extends('layouts.app')

@section('pageTitle', 'Create Dataset Comment')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')

    <h3 class="text-center">Create Comment for Dataset {{$dataset->name}}</h3>
    <br/>

    <form method="post" action="{{route('public.datasets.comments.destroy', [$dataset, $comment])}}"
          id="comment-destroy">
        @csrf
        @method('delete')
        <div class="mb-3">
            <label for="title">Title</label>
            <input class="form-control" id="title" value="{{$comment->title}}" type="text"
                   placeholder="Title..." readonly>
        </div>

        <div class="mb-3">
            <label for="comment">Comment</label>
            <textarea class="form-control" id="comment" type="text"
                      placeholder="Comment..." readonly>{{$comment->body}}</textarea>
        </div>
        <div class="float-end">
            <a href="{{route('public.datasets.comments.index', [$dataset])}}" class="action-link danger me-3">
                Cancel
            </a>
            <a href="{{route('public.datasets.comments.edit', [$dataset, $comment])}}"
               class="action-link me-3">
                Edit
            </a>
            <a class="action-link" href="#"
               onclick="document.getElementById('comment-destroy').submit()">
                Delete
            </a>
        </div>
    </form>

    @include('common.errors')

@stop
