<div class="mb-3">
    <label for="tags">Tags</label>
    @if(!blank($tags))
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
    @else
        <div class="border rounded bg-light px-3 py-2" style="font-size:.92rem; line-height:1.6;">No tags for this dataset</div>
    @endif
</div>

