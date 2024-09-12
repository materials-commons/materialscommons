<div class="row">
    <div class="col-8">
        <form>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="mql">Filter:</label>
                    <textarea class="form-control col-12" id="mql" placeholder="Filter by..."
                              rows="{{line_count($filters, 2)+1}}"></textarea>
                </div>
            </div>
        </form>
    </div>
    <div class="col-4">
        <div class="row col-12">
            <a class="btn btn-danger" href="#">Reset</a>
            <a class="btn btn-warning ml-2" href="#">Save</a>
            <a class="btn btn-success ml-2" href="#">Run</a>
        </div>

        <div class="row col-12">
            <select name="what" class="selectpicker mt-4" title="Load Saved Filter"
                    data-style="btn-light no-tt">
                <option value="proj">Annealed Samples</option>
                <option value="proj">Stress vs Strain</option>
            </select>
        </div>
    </div>
</div>