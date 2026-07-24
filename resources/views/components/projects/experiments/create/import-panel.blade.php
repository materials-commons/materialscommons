@props([
    'project',
    'excelFiles',
    'sheets',
])

<div class="card h-100">
    <div class="card-header">
        <div>
            <h2 class="h5 mb-1">Import Source</h2>
            <div class="text-muted small">
                Choose whether to create an empty study or import data from a spreadsheet source.
            </div>
        </div>

        <span class="badge text-bg-light border">Optional</span>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Source</label>

            <div class="list-group mc-study-source-options">
                <label class="list-group-item d-flex gap-3 align-items-start">
                    <input class="form-check-input mt-1 js-study-import-source"
                           type="radio"
                           name="import_source"
                           value="none"
                           checked>
                    <span>
                        <span class="fw-semibold d-block">
                            <i class="fas fa-ban text-muted me-1"></i>
                            No import
                        </span>
                        <span class="text-muted small">
                            Create an empty study and add samples, processes, files, and metadata later.
                        </span>
                    </span>
                </label>

                <label class="list-group-item d-flex gap-3 align-items-start">
                    <input class="form-check-input mt-1 js-study-import-source"
                           type="radio"
                           name="import_source"
                           value="spreadsheet">
                    <span>
                        <span class="fw-semibold d-block">
                            <i class="fas fa-file-excel text-success me-1"></i>
                            Project spreadsheet
                        </span>
                        <span class="text-muted small">
                            Choose an Excel file already uploaded to this project.
                        </span>
                    </span>
                </label>

                <label class="list-group-item d-flex gap-3 align-items-start">
                    <input class="form-check-input mt-1 js-study-import-source"
                           type="radio"
                           name="import_source"
                           value="google_sheet">
                    <span>
                        <span class="fw-semibold d-block">
                            <i class="fab fa-google-drive text-warning me-1"></i>
                            Google Sheet
                        </span>
                        <span class="text-muted small">
                            Paste a Google Sheet URL or choose an existing connected sheet.
                        </span>
                    </span>
                </label>
            </div>
        </div>

        <div id="study-import-none-panel" class="mc-study-soft-panel js-study-import-panel">
            <div class="d-flex gap-3">
                <div class="text-muted fs-4">
                    <i class="fas fa-clipboard"></i>
                </div>

                <div>
                    <div class="fw-semibold mb-1">Create an empty study</div>
                    <div class="text-muted small">
                        No import source will be used. You can add or link study data after the study is created.
                    </div>
                </div>
            </div>
        </div>

        <div id="study-import-spreadsheet-panel"
             class="mc-study-soft-panel js-study-import-panel d-none">
            <div class="d-flex align-items-start gap-3">
                <div class="text-success fs-4">
                    <i class="fas fa-file-excel"></i>
                </div>

                <div class="flex-grow-1">
                    <h3 class="h6 mb-1">Project Spreadsheet</h3>
                    <p class="text-muted small mb-3">
                        Select an Excel file that has already been uploaded to this project.
                    </p>

                    <div class="mb-0">
                        <label for="file_id" class="form-label">Select spreadsheet</label>
                        <select id="file_id"
                                name="file_id"
                                class="form-select js-study-import-field"
                                data-import-source="spreadsheet"
                                title="Select Spreadsheet"
                                disabled>
                            <option value=""></option>
                            @foreach($excelFiles as $f)
                                <option data-tokens="{{ $f->id }}"
                                        value="{{ $f->id }}">
                                    {{ $f->directory->path === "/" ? "" : $f->directory->path }}/{{ $f->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="study-import-google-sheet-panel"
             class="mc-study-soft-panel js-study-import-panel d-none">
            <div class="d-flex align-items-start gap-3">
                <div class="text-warning fs-4">
                    <i class="fab fa-google"></i>
                </div>

                <div class="flex-grow-1">
                    <h3 class="h6 mb-1">Google Sheet</h3>
                    <p class="text-muted small mb-3">
                        Paste a Google Sheet URL or choose a previously connected sheet.
                    </p>

                    <div class="mb-3">
                        <label for="url-id" class="form-label">New Sheet URL</label>
                        <div class="input-group">
                            <input class="form-control js-study-import-field"
                                   hx-get="{{ route('projects.files.sheets.resolve-google-sheet', [$project]) }}"
                                   hx-target="#google-sheet-title"
                                   hx-indicator=".htmx-indicator"
                                   hx-trigger="keyup changed delay:500ms"
                                   name="sheet_url"
                                   type="url"
                                   placeholder="Paste URL here..."
                                   id="url-id"
                                   data-import-source="google_sheet"
                                   disabled>
                            <span class="htmx-indicator input-group-text">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </div>
                        <div id="google-sheet-title" class="small mt-1"></div>
                    </div>

                    @if($sheets->count() !== 0)
                        <div class="mb-3">
                            <label for="sheet_id" class="form-label">Or use existing sheet</label>
                            <select id="sheet_id"
                                    name="sheet_id"
                                    class="form-select js-study-import-field"
                                    data-import-source="google_sheet"
                                    title="Select Google Sheet"
                                    disabled>
                                <option value=""></option>
                                @foreach($sheets as $s)
                                    <option data-tokens="{{ $s->id }}"
                                            value="{{ $s->id }}">{{ $s->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="alert alert-info mb-0">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-info-circle fa-lg mt-1"></i>

                            <div>
                                <div class="fw-semibold mb-1">Google Sheet sharing</div>
                                <p class="small mb-2">
                                    Set sharing permissions to "Anyone with the link" under General Access for the
                                    Google Sheet to be accessible.
                                </p>

                                <img src="{{ asset('images/google-sheets-share.png') }}"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-width: 300px"
                                     alt="Google Sheets sharing settings">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <a href="/mcdocs2/guides/spreadsheets.html"
               target="_blank"
               class="small text-decoration-none">
                <i class="fas fa-book-open me-1"></i>
                View spreadsheet format
            </a>

            <div class="text-muted small">
                You can create the study without importing data.
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sourceInputs = document.querySelectorAll('.js-study-import-source');
            const panels = document.querySelectorAll('.js-study-import-panel');
            const importFields = document.querySelectorAll('.js-study-import-field');

            const panelForSource = {
                none: document.getElementById('study-import-none-panel'),
                spreadsheet: document.getElementById('study-import-spreadsheet-panel'),
                google_sheet: document.getElementById('study-import-google-sheet-panel'),
            };

            function setImportSource(source) {
                panels.forEach((panel) => {
                    panel.classList.add('d-none');
                });

                if (panelForSource[source]) {
                    panelForSource[source].classList.remove('d-none');
                }

                importFields.forEach((field) => {
                    const shouldEnable = field.dataset.importSource === source;

                    field.disabled = !shouldEnable;

                    if (!shouldEnable) {
                        field.value = '';
                    }
                });

                const googleSheetTitle = document.getElementById('google-sheet-title');

                if (googleSheetTitle && source !== 'google_sheet') {
                    googleSheetTitle.innerHTML = '';
                }
            }

            sourceInputs.forEach((input) => {
                input.addEventListener('change', () => {
                    if (input.checked) {
                        setImportSource(input.value);
                    }
                });
            });

            setImportSource(document.querySelector('.js-study-import-source:checked')?.value || 'none');
        });
    </script>
@endpush
