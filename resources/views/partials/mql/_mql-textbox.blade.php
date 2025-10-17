<div class="container">
    <div class="row">
        <div class="col-12">
            <form class="ms-4">
                <div class="mb-3">
                    <label for="mql">Current Query</label>
                    <textarea class="form-control" id="mql" placeholder="Query..."
                              rows="{{line_count($query, 2)+1}}">{{$query}}</textarea>
                </div>
                <a class="float-left" href="#" @click="toggleShowSavedQueries()">Saved Queries</a>
                <div class="float-right">
                    <a class="btn btn-danger"
                       href="{{route('projects.entities.index', ['project' => $project->id, 'category' => 'experimental'])}}">Reset</a>
                    @if($query == "")
                        <a class="btn btn-warning disabled" href="#" disabled="true">Save</a>
                        <a class="btn btn-success disabled" href="#" disabled="true">
                            Run
                        </a>
                    @else
                        <button class="btn btn-warning"
                                hx-post="{{route('projects.mql.save.text', [$project])}}"
                                hx-trigger="click"
                                hx-target="#query-text"
                                hx-include="#mql-selection"
                                data-bs-toggle="modal" href="#mql-save-query-dialog">Save
                        </button>
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
                    <td>{{$query->description}}</td>
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
