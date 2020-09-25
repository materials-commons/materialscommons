<div class="form-group">
    <label for="authors">Authors and Affiliations</label>
    <ul class="list-inline">
        @foreach(explode(";", $authors) as $author)
            <li class="list-inline-item"><span
                        class="fs-9 grey-5">{{Illuminate\Support\Str::of($author)->before('(')}}</span></li>
        @endforeach
    </ul>
</div>