@if(!blank($dataset->funding))
    <div class="form-group">
        <label for="funding">Funding</label>
        <p id="funding">
            {{$dataset->funding}}
        </p>
    </div>
@endif