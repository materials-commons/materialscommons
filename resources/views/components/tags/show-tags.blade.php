@if(!blank($tags))
    <div class="form-group">
        <label for="tags">Tags</label>
        <ul class="list-inline">
            @foreach($tags as $tag)
                <li class="list-inline-item mt-1">
                    <a class="badge badge-success fs-11 td-none"
                       href="#">
                        {{$tag->name}}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif