@extends('layouts.app')

@section('pageTitle', 'Create Dataset Comment')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    <h3 class="text-center">Update Comment in Dataset {{$dataset->name}}</h3>
    <br/>

    <form method="post" action="{{route('public.datasets.comments.update', [$dataset, $comment])}}"
          id="comment-update">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="title">Title</label>
            <input class="form-control" id="title" name="title" value="{{$comment->title}}" type="text"
                   placeholder="Title..." required>
        </div>

        <div class="mb-3">
            <label for="comment">Comment</label>
            <textarea class="form-control" id="comment" name="body" type="text"
                      placeholder="Comment..." required>{{$comment->body}}</textarea>
        </div>
        <div class="float-end">
            <a href="{{route('public.datasets.comments.index', [$dataset])}}" class="action-link danger me-3">
                Cancel
            </a>
            <a class="action-link" href="#"
               onclick="document.getElementById('comment-update').submit()">
                Update
            </a>
        </div>
    </form>

    @include('common.errors')

@stop
