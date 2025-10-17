<div class="modal" tabindex="-1" id="add-google-sheet-modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Google Sheet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Add new Google Sheet to {{$project->name}}</p>
                <form id="add-sheet-form" method="post"
                      action="{{route('projects.files.sheets.add-google-sheet', [$project])}}">
                    @csrf
                    <div class="mb-3">
                        <label>Google Sheet URL</label>
                        <input class="form-control" name="sheet_url" value=""
                               hx-get="{{route('projects.files.sheets.resolve-google-sheet', [$project])}}"
                               hx-target="#google-sheet-title"
                               hx-indicator=".htmx-indicator"
                               hx-trigger="keyup changed delay:500ms"
                               type="text" placeholder="Google Sheet URL..." required>
                    </div>
                    <span class="htmx-indicator"><i class="fas fa-spinner fa-spin"></i></span>
                    <div id="google-sheet-title"></div>
                    <div class="float-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" type="submit" data-dismiss="modal"
                           onclick="document.getElementById('add-sheet-form').submit()">Add Google Sheet</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>