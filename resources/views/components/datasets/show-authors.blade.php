@if(!blank($authors))
    <div class="form-group mt-2">
        <label for="authors">Authors</label>
        <ul class="list-inline">
            @foreach(explode(";", $authors) as $author)
                <li class="list-inline-item">
                    <span
                            class="fs-9 grey-5">{{Illuminate\Support\Str::of($author)->before('(')}}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif