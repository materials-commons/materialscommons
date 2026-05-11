@php
    $title = 'Prototype - Create Study Import';
    $heading = 'Create Study';
    $subheading = 'Prototype of a more interactive create flow with queued spreadsheet import feedback.';
@endphp

@extends('prototype.experiment-import._prototype-shell')

@section('prototype-content')
    <div class="row g-4">
        <div class="col-xl-5 col-lg-6">
            <div class="mc-import-card mb-4">
                <div class="mc-import-card-header">
                    <h2 class="h5 mb-1">Study Details</h2>
                    <div class="text-muted small">Basic information users provide before importing data.</div>
                </div>

                <div class="mc-import-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Study Name</label>
                        <input type="text" class="form-control" value="Heat Treatment Study">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Summary</label>
                        <input type="text" class="form-control" value="Processing and characterization workflow for alloy coupons">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" rows="7">This study tracks heat treatment, microscopy, hardness testing, and tensile measurements for a small batch of samples.</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary">Cancel</button>
                        <a href="{{ route('prototype.experiment-import.status') }}" class="btn btn-primary">
                            Create Study
                        </a>
                    </div>
                </div>
            </div>

            <div class="mc-soft-panel">
                <div class="d-flex gap-3">
                    <div class="text-primary fs-4">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div>
                        <div class="fw-semibold mb-1">Design idea</div>
                        <div class="text-muted small">
                            The user can create the study immediately, then the import panel becomes a live status area.
                            For the prototype, the button links directly to the status screen.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7 col-lg-6">
            <div class="mc-import-card mb-4">
                <div class="mc-import-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="h5 mb-1">Import Spreadsheet</h2>
                            <div class="text-muted small">Load attributes, samples, processes, measurements, and workflow.</div>
                        </div>
                        <span class="badge text-bg-primary">Optional</span>
                    </div>
                </div>

                <div class="mc-import-card-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Source</label>

                            <div class="list-group">
                                <label class="list-group-item d-flex gap-3 align-items-start">
                                    <input class="form-check-input mt-1" type="radio" name="source" checked>
                                    <span>
                                        <span class="fw-semibold d-block">
                                            <i class="fas fa-file-excel text-success me-1"></i>
                                            Project spreadsheet
                                        </span>
                                        <span class="text-muted small">Choose an Excel file already uploaded to this project.</span>
                                    </span>
                                </label>

                                <label class="list-group-item d-flex gap-3 align-items-start">
                                    <input class="form-check-input mt-1" type="radio" name="source">
                                    <span>
                                        <span class="fw-semibold d-block">
                                            <i class="fab fa-google-drive text-warning me-1"></i>
                                            Google Sheet
                                        </span>
                                        <span class="text-muted small">Paste a Google Sheet URL and import directly.</span>
                                    </span>
                                </label>

                                <label class="list-group-item d-flex gap-3 align-items-start">
                                    <input class="form-check-input mt-1" type="radio" name="source">
                                    <span>
                                        <span class="fw-semibold d-block">
                                            <i class="fas fa-ban text-muted me-1"></i>
                                            No import
                                        </span>
                                        <span class="text-muted small">Create an empty study and add data later.</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Selected Spreadsheet</label>
                            <select class="form-select mb-3">
                                <option selected>heat-treatment-study.xlsx</option>
                                <option>coupon-characterization.xlsx</option>
                                <option>workflow-template.xlsx</option>
                            </select>

                            <label class="form-label fw-semibold">Google Sheet URL</label>
                            <input type="text" class="form-control mb-3" placeholder="https://docs.google.com/spreadsheets/d/...">

                            <div class="mc-soft-panel">
                                <div class="fw-semibold mb-2">What happens next?</div>
                                <ol class="small text-muted mb-0 ps-3">
                                    <li>The study is created immediately.</li>
                                    <li>The spreadsheet import starts in the queue.</li>
                                    <li>The page switches to live import status.</li>
                                    <li>Results, validation messages, and logs appear as they are available.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center justify-content-between">
                        <a href="/mcdocs2/guides/spreadsheets.html" target="_blank" class="small">
                            <i class="fas fa-book-open me-1"></i>
                            View spreadsheet format
                        </a>

                        <a href="{{ route('prototype.experiment-import.status') }}" class="btn btn-success">
                            <i class="fas fa-play me-1"></i>
                            Create and Import
                        </a>
                    </div>
                </div>
            </div>

            <div class="mc-import-card">
                <div class="mc-import-card-header">
                    <h2 class="h5 mb-1">Pre-flight Preview</h2>
                    <div class="text-muted small">Optional future concept: show what will be imported before queueing.</div>
                </div>

                <div class="mc-import-card-body">
                    <div class="row g-3">
                        <div class="col-sm-3">
                            <div class="mc-metric">
                                <div class="mc-metric-value text-primary">4</div>
                                <div class="text-muted small">Worksheets</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mc-metric">
                                <div class="mc-metric-value text-success">24</div>
                                <div class="text-muted small">Samples</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mc-metric">
                                <div class="mc-metric-value text-info">8</div>
                                <div class="text-muted small">Processes</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mc-metric">
                                <div class="mc-metric-value text-warning">3</div>
                                <div class="text-muted small">Warnings</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Preview found 3 minor formatting warnings. Import can continue.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
