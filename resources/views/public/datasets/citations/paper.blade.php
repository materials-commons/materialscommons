<div>
    <form>
        <div class="form-group mt-2">
            <label>Paper</label>
            <p>{{$paper->title[0]}}</p>
        </div>
        @foreach($paper->reference as $reference)
        @endforeach
    </form>
</div>
