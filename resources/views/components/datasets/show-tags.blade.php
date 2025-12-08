@if(!blank($tags))
    <div class="mb-3">
        <label for="tags">Tags</label>
        <ul class="list-inline">
            @foreach($tags as $tag)
                <li class="list-inline-item mt-1">
                    <a class="badge text-bg-success fs-11 td-none text-white"
                       href="{{route('public.tags.search', ['tag' => $tag->name])}}">
                        {{$tag->name}}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
