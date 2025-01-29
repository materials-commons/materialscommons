<div>
    <h5>String Filters</h5>
    <p>Select the type of filter you want to apply. By default your filter will be connected
        to other filters by "AND". For example x = 'mg' AND y >= 3. You can switch this to an "OR".</p>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <div class="input-group-text">
                <input type="checkbox" aria-label="">
            </div>
            <select class="custom-select">
                <option value="and" selected>And</option>
                <option value="or">Or</option>
            </select>
            <span class="input-group-text">Any Of</span>
        </div>
        <input class="form-control" placeholder="Comma separated attribute values...">
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
            <span class="input-group-text">=</span>
        </div>
        <input class="form-control" placeholder="Single attribute value...">
    </div>
</div>