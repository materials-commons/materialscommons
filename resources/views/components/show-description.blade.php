<div class="form-group">
    <label for="description">Description</label>
    @foreach(explode("\n", $description) as $paragraph)
        @if (!blank($paragraph))
            <p>
                {{$paragraph}}
            </p>
        @endif
    @endforeach
</div>