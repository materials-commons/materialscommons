<form class="ml-4" id="mql-query">
    <div class="form-group">
        <label for="mql">Current Filters</label>
        <textarea class="form-control" id="mql" placeholder="Filters...">{{$filters}}</textarea>
    </div>
    <div class="float-right">
        <button class="btn btn-danger">Clear Filters</button>
        <button class="btn btn-success">Apply Filters</button>
    </div>
</form>