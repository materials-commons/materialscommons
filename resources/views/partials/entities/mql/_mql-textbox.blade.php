<form class="ml-4" id="mql-query">
    <div class="form-group">
        <label for="mql">Current Query</label>
        <textarea class="form-control" id="mql" placeholder="Query...">{{$filters}}</textarea>
    </div>
    <a class="float-left" href="#">Saved Queries</a>
    <div class="float-right">
        <a class="btn btn-danger" href="{{route('projects.entities.index', [$project])}}">Reset</a>
        <a class="btn btn-warning" href="#">Save</a>
        <a class="btn btn-success" href="#" onclick="document.getElementById('mql-selection').submit()">
            Run
        </a>
    </div>
</form>