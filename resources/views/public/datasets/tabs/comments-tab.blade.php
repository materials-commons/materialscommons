@auth
    <a class="action-link float-right"
       href="{{route('public.datasets.comments.create', [$dataset])}}">
        <i class="fas fa-plus mr-2"></i>Add Comment
    </a>
@endauth
@forelse($dataset->comments as $comment)

    <div class="row col-12">
        <form class="col-10">
            <div class="form-group">
                <label for="comment_{{$comment->id}}">{{$comment->title}}</label>
                <textarea class="form-control" id="comment_{{$comment->id}}" readonly
                          style="min-width:100%">{{$comment->body}}</textarea>
                <span>
                    <small>{{$comment->owner->name}}</small>
                    <small class="ml-2">Last Updated: {{$comment->updated_at->diffForHumans()}}</small>
                    @auth
                        @if ($user->id === $comment->owner->id)
                            <small class="ml-2">
                            <a href="{{route('public.datasets.comments.edit', [$dataset, $comment])}}">edit</a>
                        </small>
                            <small class="ml-2">
                            <a href="{{route('public.datasets.comments.delete', [$dataset, $comment])}}">delete</a>
                        </small>
                        @endif
                    @endauth
                </span>
            </div>
        </form>
    </div>
@empty
    <p>No comments</p>
@endforelse