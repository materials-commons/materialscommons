<div class="container">
    <div class="row">
        <div class="col-12">
            <form class="ml-4" id="mql-query">
                <div class="form-group">
                    <label for="mql">Current Query</label>
                    <textarea class="form-control" id="mql" placeholder="Query..."
                              style="height: 75px">{{$filters}}</textarea>
                </div>
                <a class="float-left" href="#" @click="toggleShowSavedQueries()">Saved Queries</a>
                <div class="float-right">
                    <a class="btn btn-danger" href="{{route('projects.entities.index', [$project])}}">Reset</a>
                    <a class="btn btn-warning" href="#">Save</a>
                    <a class="btn btn-success" href="#" onclick="document.getElementById('mql-selection').submit()">
                        Run
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div id="saved-queries" style="display: none" x-show="showSavedQueries" class="row">
        <br>
        <a href="#" @click="toggleShowSavedQueries()" class="mt-6">Close</a>
        Showing saved queries
    </div>
    <br>
</div>