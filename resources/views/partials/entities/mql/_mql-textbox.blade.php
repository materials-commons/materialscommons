<div class="container">
    <div class="row">
        <div class="col-12">
            <form class="ml-4">
                <div class="form-group">
                    <label for="mql">Current Query</label>
                    <textarea class="form-control" id="mql" placeholder="Query..."
                              style="height: 75px">{{$query}}</textarea>
                </div>
                <a class="float-left" href="#" @click="toggleShowSavedQueries()">Saved Queries</a>
                <div class="float-right">
                    <a class="btn btn-danger" href="{{route('projects.entities.index', [$project])}}">Reset</a>
                    @if($query == "")
                        <a class="btn btn-warning disabled" href="#" disabled="true">Save</a>
                        <a class="btn btn-success disabled" href="#" disabled="true">
                            Run
                        </a>
                    @else
                        <a class="btn btn-warning" data-toggle="modal" href="#mql-save-query-dialog">Save</a>
                        <a class="btn btn-success" href="#" onclick="document.getElementById('mql-selection').submit()">
                            Run
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div id="saved-queries" style="display: none" x-show="showSavedQueries" class="row">
        <br>
        <a href="#" @click="toggleShowSavedQueries()" class="mt-6">Close</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Query</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($queries as $query)
                <tr>
                    <td>{{$query->name}}</td>
                    <td>{{$query->descripton}}</td>
                    <td>{{$query->queryText()}}</td>
                    <td>
                        <a class="btn btn-success" href="{{route('projects.mql.run', [$project, $query])}}">
                            Run
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br>
    @include('app.dialogs.save-query-dialog')
</div>