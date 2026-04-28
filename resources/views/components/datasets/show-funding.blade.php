@if(!blank($dataset->funding))
    <div class="mb-3">
        <label for="funding">Funding</label>
        <p id="funding">
            {{$dataset->funding}}
        </p>
    </div>
@endif