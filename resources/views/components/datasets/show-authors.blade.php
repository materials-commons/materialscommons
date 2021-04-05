@if(!blank($authors))
    <div class="form-group mt-2">
        <label for="authors">Authors</label>
        <ul class="list-inline">
            @foreach($authors as $author)
                <li class="list-inline-item">
                    <span class="fs-10 grey-5">{{$author['name']}}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
