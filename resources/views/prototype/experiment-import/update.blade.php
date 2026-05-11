@php
    $title = 'Prototype - Update Study Import';
    $heading = 'Update Study';
    $subheading = 'Prototype of reloading an existing study from a spreadsheet or Google Sheet.';
@endphp

@extends('prototype.experiment-import._prototype-shell')

@section('prototype-content')
    <div class="row g-4">
        <div class="col-xl-5 col-lg-6">
            <div class="mc-import-card mb-4">
                <div class="mc-import-card-header">
                    <h2 class="h5 mb-1">Current Study</h2>
                    <div class="text-muted small">Existing study metadata.</div>
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
                        <textarea class="form-control" rows="6">This study tracks heat treatment, microscopy, hardness testing, and tensile measurements for a small batch of samples.</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary">Cancel</button>
                        <button type="button" class="btn btn-primary">Save Details</button>
                    </div>
                </div>
            </div>

            <div class="mc-import-card">
                <div class="mc-import-card-header">
                    <h2 class="h5 mb-1">Current Import Source</h2>
                    <div class="text-muted small">This is what the study was last loaded from.</div>
                </div>

                <div class="mc-import-card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-sm-4">Source</dt>
                        <dd class="col-sm-8">
                            <i class="fas fa-file-excel text-success me-1"></i>
                            Project spreadsheet
                        </dd>

                        <dt class="col-sm-4">File</dt>
                        <dd class="col-sm-8">/experiments/imports/heat-treatment-study.xlsx</dd>

                        <dt class="col-sm-4">Last Import</dt>
                        <dd class="col-sm-8">May 7, 2026 10:24 AM</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge text-bg-success">Completed</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-xl-7 col-lg-6">
            <div class="mc-import-card mb-4">
                <div class="mc-import-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="h5 mb-1">Reload Study Data</h2>
                            <div class="text-muted small">Run a new queued import and show live progress on the page.</div>
                        </div>
                        <span class="badge text-bg-warning">Destructive potential</span>
                    </div>
                </div>

                <div class="mc-import-card-body">
                    <div class="alert alert-info">
                        <div class="fw-semibold mb-1">
                            <i class="fas fa-info-circle me-1"></i>
                            Suggested interaction
                        </div>
                        <div class="small">
                            User clicks reload, confirms the import strategy, then stays on a live status page.
                            Existing data impact should be explicit before starting.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Import Strategy</label>

                        <div class="list-group">
                            <label class="list-group-item d-flex gap-3 align-items-start">
                                <input class="form-check-input mt-1" type="radio" name="strategy" checked>
                                <span>
                                    <span class="fw-semibold d-block">Merge new data into existing study</span>
                                    <span class="text-muted small">Create missing samples, processes, and attributes. Keep existing records.</span>
                                </span>
                            </label>

                            <label class="list-group-item d-flex gap-3 align-items-start">
                                <input class="form-check-input mt-1" type="radio" name="strategy">
                                <span>
                                    <span class="fw-semibold d-block">Replace imported data</span>
                                    <span class="text-muted small">Remove data created by the previous import before loading the new spreadsheet.</span>
                                </span>
                            </label>

                            <label class="list-group-item d-flex gap-3 align-items-start">
                                <input class="form-check-input mt-1" type="radio" name="strategy">
                                <span>
                                    <span class="fw-semibold d-block">Validate only</span>
                                    <span class="text-muted small">Run validation and show results without changing the study.</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Spreadsheet File</label>
                            <select class="form-select">
                                <option selected>heat-treatment-study-v2.xlsx</option>
                                <option>heat-treatment-study.xlsx</option>
                                <option>workflow-template.xlsx</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Google Sheet URL</label>
                            <input type="text" class="form-control" placeholder="Optional Google Sheet URL">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Typical imports take 30 seconds to several minutes.
                        </div>

                        <a href="{{ route('prototype.experiment-import.status') }}" class="btn btn-success">
                            <i class="fas fa-sync-alt me-1"></i>
                            Reload and Watch Status
                        </a>
                    </div>
                </div>
            </div>

            <div class="mc-import-card">
                <div class="mc-import-card-header">
                    <h2 class="h5 mb-1">Recent Import Runs</h2>
                    <div class="text-muted small">Useful context before reloading.</div>
                </div>

                <div class="mc-import-card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Started</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th class="text-end">Warnings</th>
                            <th class="text-end">Errors</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>May 7, 2026 10:24 AM</td>
                            <td>heat-treatment-study.xlsx</td>
                            <td><span class="badge text-bg-success">Completed</span></td>
                            <td class="text-end">2</td>
                            <td class="text-end">0</td>
                        </tr>
                        <tr>
                            <td>May 6, 2026 4:12 PM</td>
                            <td>Google Sheet</td>
                            <td><span class="badge text-bg-danger">Failed</span></td>
                            <td class="text-end">4</td>
                            <td class="text-end">1</td>
                        </tr>
                        <tr>
                            <td>May 5, 2026 9:08 AM</td>
                            <td>workflow-template.xlsx</td>
                            <td><span class="badge text-bg-success">Completed</span></td>
                            <td class="text-end">0</td>
                            <td class="text-end">0</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
