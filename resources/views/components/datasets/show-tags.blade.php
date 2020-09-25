<div class="form-group">
    <label for="tags">Tags</label>
    <ul class="list-inline">
        @foreach($tags as $tag)
            <li class="list-inline-item">
                <a class="badge badge-success fs-11 td-none"
                   href="{{route('public.tags.search', ['tag' => $tag->name])}}">
                    {{$tag->name}}
                </a>
            </li>
        @endforeach
    </ul>
</div>