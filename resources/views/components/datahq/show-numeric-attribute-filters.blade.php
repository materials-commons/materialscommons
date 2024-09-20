<div>
    <h5>Numeric Filters</h5>
    <p>Select the type of filter you want to apply. By default your filter will be connected
        to other filters by "AND". For example x <= 5 AND y >= 3. You can switch this to an "OR".</p>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <div class="input-group-text">
                <input type="checkbox" aria-label="">
            </div>
            <select class="custom-select">
                <option value="and" selected>And</option>
                <option value="or">Or</option>
            </select>
            <span class="input-group-text">Range Filter</span>
        </div>
        <input class="form-control" aria-label="" placeholder="From (inclusive)...">
        <input class="form-control" aria-label="" placeholder="To (inclusive)...">
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <div class="input-group-text">
                <input type="checkbox" aria-label="">
            </div>
            <select class="custom-select">
                <option value="and" selected>And</option>
                <option value="or">Or</option>
            </select>
            <span class="input-group-text"><=</span>
        </div>
        <input type="text" class="form-control" aria-label="" placeholder="Less Than or Equal to Value..">
    </div>

    <div class="input-group">
        <div class="input-group-prepend">
            <div class="input-group-text">
                <input type="checkbox" aria-label="">
            </div>
            <select class="custom-select">
                <option value="and" selected>And</option>
                <option value="or">Or</option>
            </select>
            <span class="input-group-text">>=</span>
        </div>
        <input type="text" class="form-control" aria-label="" placeholder="Greater Than or Equal to Value..">
    </div>
</div>