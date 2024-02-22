{{--<div class="form-group">--}}
<label for="url-id">Enter New Google Sheet</label>
<input class="form-control"
       hx-get="{{route('projects.files.sheets.resolve-google-sheet', [$project])}}"
       hx-target="#google-sheet-title"
       hx-indicator=".htmx-indicator"
       hx-trigger="keyup changed delay:500ms"
       name="sheet_url" type="url" placeholder="Google Sheet URL.."
       id="url-id">
<span class="htmx-indicator"><i class="fas fa-spinner fa-spin"></i></span>
<div id="google-sheet-title"></div>
{{--</div>--}}