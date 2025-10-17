<div>
    <form>
        <div class="mb-3 mt-2">
            <label>Paper</label>
            <p>{{$paper->title[0]}}</p>
        </div>
        @foreach($paper->reference as $reference)
        @endforeach
    </form>
</div>
