<div class="row me-2 ms-1">
    {{-- Google Sheets Option --}}
    <div class="col-12 white-box">
        <h5 class="text-centerx mt-3 me-2 font-weight-bold">
            <i class="fab fa-google me-2"></i> Google Sheets
        </h5>
        <hr>
        <div class="mb-3">
            <label for="url-id">New Sheet URL</label>
            <div class="input-group">
                <input class="form-control"
                       hx-get="{{route('projects.files.sheets.resolve-google-sheet', [$project])}}"
                       hx-target="#google-sheet-title"
                       hx-indicator=".htmx-indicator"
                       hx-trigger="keyup changed delay:500ms"
                       name="sheet_url" type="url"
                       placeholder="Paste URL here..."
                       id="url-id">
                <span class="htmx-indicator input-group-text"><i
                        class="fas fa-spinner fa-spin"></i></span>
            </div>
            <div id="google-sheet-title" class="small mt-1"></div>
        </div>

        @if($sheets->count() !== 0)
            <div class="mt-3">
                <label for="sheet_id">Or use existing sheet</label>
                <select name="sheet_id" class="form-select" title="Select Google Sheet">
                    <option value=""></option>
                    @foreach($sheets as $s)
                        <option data-tokens="{{$s->id}}"
                                value="{{$s->id}}">{{$s->title}}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="alert alert-info mt-3">
            <div class="d-flex align-items-center">
                <div>
                    <p>
                        <i class="fas fa-info-circle fa-lg me-3"></i>
                        <strong>Important:</strong>
                        Set sharing permissions to "Anyone with the link"
                        under General Access for the Google Sheet to be accessible.
                    </p>
                    <img src="{{asset('images/google-sheets-share.png')}}"
                         class="img-fluid mt-2 rounded shadow-sm"
                         style="max-width: 300px"
                         alt="Google Sheets sharing settings">
                </div>

            </div>
        </div>
    </div>
</div>
