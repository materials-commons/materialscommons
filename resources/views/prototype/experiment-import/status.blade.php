@php
    $title = 'Prototype - Import Status';
    $heading = 'Importing Study Spreadsheet';
    $subheading = 'Live-style prototype showing queued job progress, validation, process results, and logs.';
@endphp

@extends('prototype.experiment-import._prototype-shell')

@section('prototype-content')
    <div class="row g-4">
        <div class="col-xl-4">
            <div class="mc-import-card mc-sticky-status mb-4">
                <div class="mc-import-card-header">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h2 class="h5 mb-1">Import Status</h2>
                            <div class="text-muted small">heat-treatment-study-v2.xlsx</div>
                        </div>

                        <span id="status-badge" class="badge text-bg-primary">
                            Running
                        </span>
                    </div>
                </div>

                <div class="mc-import-card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="fw-semibold">Overall Progress</span>
                            <span id="progress-label">62%</span>
                        </div>
                        <div class="progress" style="height: .75rem;">
                            <div id="progress-bar"
                                 class="progress-bar progress-bar-striped progress-bar-animated"
                                 style="width: 62%">
                            </div>
                        </div>
                    </div>

                    <div class="mc-soft-panel mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <span id="live-dot" class="mc-status-dot mc-status-dot-running"></span>
                            <span id="live-status-text" class="fw-semibold">Processing workflow rows</span>
                        </div>

                        <div class="small text-muted">
                            Started May 7, 2026 11:06 AM by Ada Lovelace
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="mc-metric">
                                <div class="mc-metric-value text-success" id="samples-count">24</div>
                                <div class="text-muted small">Samples</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mc-metric">
                                <div class="mc-metric-value text-info" id="processes-count">8</div>
                                <div class="text-muted small">Processes</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mc-metric">
                                <div class="mc-metric-value text-primary" id="attributes-count">146</div>
                                <div class="text-muted small">Attributes</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mc-metric">
                                <div class="mc-metric-value text-warning" id="warnings-count">3</div>
                                <div class="text-muted small">Warnings</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="mc-step">
                            <div class="mc-step-icon mc-step-icon-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Queued</div>
                                <div class="text-muted small">Job accepted by queue worker.</div>
                            </div>
                        </div>

                        <div class="mc-step">
                            <div class="mc-step-icon mc-step-icon-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Read Spreadsheet</div>
                                <div class="text-muted small">4 worksheets found and parsed.</div>
                            </div>
                        </div>

                        <div class="mc-step">
                            <div id="validate-step-icon" class="mc-step-icon mc-step-icon-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Validate</div>
                                <div class="text-muted small">Completed with 3 warnings.</div>
                            </div>
                        </div>

                        <div class="mc-step">
                            <div id="process-step-icon" class="mc-step-icon mc-step-icon-running">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Process Data</div>
                                <div class="text-muted small" id="process-step-text">Creating workflow and process records.</div>
                            </div>
                        </div>

                        <div class="mc-step">
                            <div id="finish-step-icon" class="mc-step-icon mc-step-icon-waiting">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Finish</div>
                                <div class="text-muted small" id="finish-step-text">Waiting for processing to complete.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-envelope me-1"></i>
                            Email me when complete
                        </button>

                        <a href="{{ route('prototype.experiment-import.update') }}" class="btn btn-outline-primary">
                            Back to Study
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div id="complete-alert" class="alert alert-success d-none">
                <div class="d-flex align-items-start gap-3">
                    <div class="fs-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">Spreadsheet import completed</div>
                        <div class="small">
                            The study was updated successfully. Review process results and validation warnings below.
                        </div>
                    </div>
                </div>
            </div>

            <div class="mc-import-card mb-4">
                <div class="mc-import-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="h5 mb-1">Import Results</h2>
                            <div class="text-muted small">Review what was created or updated by this import.</div>
                        </div>

                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-download me-1"></i>
                                Export
                            </button>
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i>
                                Re-run
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mc-import-card-body">
                    <ul class="nav nav-tabs" id="importResultTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active"
                                    id="process-results-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#process-results"
                                    type="button"
                                    role="tab">
                                <i class="fas fa-project-diagram me-1"></i>
                                Process Results
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="validation-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#validation"
                                    type="button"
                                    role="tab">
                                <i class="fas fa-clipboard-check me-1"></i>
                                Validation
                                <span class="badge text-bg-warning ms-1">3</span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="log-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#log"
                                    type="button"
                                    role="tab">
                                <i class="fas fa-terminal me-1"></i>
                                Log
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="process-results" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div class="fw-semibold">Process Creation Summary</div>
                                    <div class="text-muted small">Rows grouped by spreadsheet workflow/process section.</div>
                                </div>

                                <input type="search" class="form-control form-control-sm w-auto" placeholder="Filter results">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Process</th>
                                        <th>Type</th>
                                        <th class="text-end">Inputs</th>
                                        <th class="text-end">Outputs</th>
                                        <th class="text-end">Attributes</th>
                                        <th class="text-end">Measurements</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">Coupon Registration</div>
                                            <div class="text-muted small">Worksheet: samples</div>
                                        </td>
                                        <td>Sample setup</td>
                                        <td class="text-end">0</td>
                                        <td class="text-end">24</td>
                                        <td class="text-end">48</td>
                                        <td class="text-end">0</td>
                                        <td><span class="badge text-bg-success">Created</span></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="fw-semibold">Solution Treatment</div>
                                            <div class="text-muted small">Worksheet: workflow</div>
                                        </td>
                                        <td>Heat treatment</td>
                                        <td class="text-end">24</td>
                                        <td class="text-end">24</td>
                                        <td class="text-end">18</td>
                                        <td class="text-end">0</td>
                                        <td><span class="badge text-bg-success">Created</span></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="fw-semibold">Aging Treatment</div>
                                            <div class="text-muted small">Worksheet: workflow</div>
                                        </td>
                                        <td>Heat treatment</td>
                                        <td class="text-end">24</td>
                                        <td class="text-end">24</td>
                                        <td class="text-end">24</td>
                                        <td class="text-end">0</td>
                                        <td><span class="badge text-bg-success">Created</span></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="fw-semibold">Optical Microscopy</div>
                                            <div class="text-muted small">Worksheet: measurements</div>
                                        </td>
                                        <td>Characterization</td>
                                        <td class="text-end">12</td>
                                        <td class="text-end">12</td>
                                        <td class="text-end">9</td>
                                        <td class="text-end">36</td>
                                        <td><span class="badge text-bg-warning">Warnings</span></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="fw-semibold">Hardness Testing</div>
                                            <div class="text-muted small">Worksheet: measurements</div>
                                        </td>
                                        <td>Mechanical testing</td>
                                        <td class="text-end">24</td>
                                        <td class="text-end">24</td>
                                        <td class="text-end">12</td>
                                        <td class="text-end">72</td>
                                        <td><span class="badge text-bg-success">Created</span></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="fw-semibold">Tensile Testing</div>
                                            <div class="text-muted small">Worksheet: measurements</div>
                                        </td>
                                        <td>Mechanical testing</td>
                                        <td class="text-end">8</td>
                                        <td class="text-end">8</td>
                                        <td class="text-end">10</td>
                                        <td class="text-end">24</td>
                                        <td><span class="badge text-bg-success">Created</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="validation" role="tabpanel">
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <div class="mc-metric">
                                        <div class="mc-metric-value text-success">28</div>
                                        <div class="text-muted small">Checks Passed</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mc-metric">
                                        <div class="mc-metric-value text-warning">3</div>
                                        <div class="text-muted small">Warnings</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mc-metric">
                                        <div class="mc-metric-value text-danger">0</div>
                                        <div class="text-muted small">Errors</div>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <div class="fw-semibold">Missing optional units</div>
                                            <div class="text-muted small">
                                                Worksheet <code>measurements</code>, rows 18-21.
                                                Hardness values do not specify units. Defaulted to HV.
                                            </div>
                                        </div>
                                        <span class="badge text-bg-warning mc-validation-badge">Warning</span>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <div class="fw-semibold">Unrecognized column ignored</div>
                                            <div class="text-muted small">
                                                Worksheet <code>samples</code>, column <code>operator_notes_v2</code>.
                                                The column was not imported.
                                            </div>
                                        </div>
                                        <span class="badge text-bg-warning mc-validation-badge">Warning</span>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <div class="fw-semibold">Duplicate attribute names normalized</div>
                                            <div class="text-muted small">
                                                Attribute <code>temperature</code> appeared with different capitalization.
                                                Values were normalized to <code>Temperature</code>.
                                            </div>
                                        </div>
                                        <span class="badge text-bg-warning mc-validation-badge">Warning</span>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <div class="fw-semibold">All required worksheets present</div>
                                            <div class="text-muted small">
                                                Required worksheets were found: samples, workflow, measurements.
                                            </div>
                                        </div>
                                        <span class="badge text-bg-success mc-validation-badge">Passed</span>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div>
                                            <div class="fw-semibold">No unresolved sample references</div>
                                            <div class="text-muted small">
                                                Every process input and output references a known sample.
                                            </div>
                                        </div>
                                        <span class="badge text-bg-success mc-validation-badge">Passed</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="log" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div class="fw-semibold">Import Log</div>
                                    <div class="text-muted small">Streaming-style log output from the queued job.</div>
                                </div>

                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary">
                                        <i class="fas fa-copy me-1"></i>
                                        Copy
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary">
                                        <i class="fas fa-download me-1"></i>
                                        Download
                                    </button>
                                </div>
                            </div>

                            <div id="log-output" class="mc-log">
                                <div class="mc-log-line"><span class="mc-log-time">11:06:02</span> <span class="mc-log-info">INFO</span> Import job queued for study Heat Treatment Study</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:05</span> <span class="mc-log-info">INFO</span> Worker picked up job from globus queue</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:06</span> <span class="mc-log-info">INFO</span> Reading spreadsheet heat-treatment-study-v2.xlsx</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:09</span> <span class="mc-log-success">OK</span> Found worksheets: samples, workflow, measurements, files</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:12</span> <span class="mc-log-success">OK</span> Parsed 24 sample rows</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:14</span> <span class="mc-log-warning">WARN</span> Missing optional units in measurements rows 18-21</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:16</span> <span class="mc-log-warning">WARN</span> Ignoring unrecognized column operator_notes_v2</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:20</span> <span class="mc-log-info">INFO</span> Creating process: Coupon Registration</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:24</span> <span class="mc-log-info">INFO</span> Creating process: Solution Treatment</div>
                                <div class="mc-log-line"><span class="mc-log-time">11:06:29</span> <span class="mc-log-info">INFO</span> Creating process: Aging Treatment</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mc-import-card">
                <div class="mc-import-card-header">
                    <h2 class="h5 mb-1">Suggested Next Actions</h2>
                    <div class="text-muted small">Shown after the import completes.</div>
                </div>

                <div class="mc-import-card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="#" class="text-decoration-none">
                                <div class="mc-soft-panel h-100">
                                    <div class="fs-4 text-primary mb-2">
                                        <i class="fas fa-sitemap"></i>
                                    </div>
                                    <div class="fw-semibold text-body">View Workflow</div>
                                    <div class="text-muted small">Inspect the imported process graph.</div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="#" class="text-decoration-none">
                                <div class="mc-soft-panel h-100">
                                    <div class="fs-4 text-success mb-2">
                                        <i class="fas fa-vials"></i>
                                    </div>
                                    <div class="fw-semibold text-body">View Samples</div>
                                    <div class="text-muted small">Review imported sample records.</div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="#" class="text-decoration-none">
                                <div class="mc-soft-panel h-100">
                                    <div class="fs-4 text-warning mb-2">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                    <div class="fw-semibold text-body">Fix Warnings</div>
                                    <div class="text-muted small">Review validation warnings.</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Prototype-only fake "live" progress. Replace with Livewire polling/events later. --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const progressBar = document.getElementById('progress-bar');
                const progressLabel = document.getElementById('progress-label');
                const statusBadge = document.getElementById('status-badge');
                const liveDot = document.getElementById('live-dot');
                const liveStatusText = document.getElementById('live-status-text');
                const completeAlert = document.getElementById('complete-alert');
                const processStepIcon = document.getElementById('process-step-icon');
                const processStepText = document.getElementById('process-step-text');
                const finishStepIcon = document.getElementById('finish-step-icon');
                const finishStepText = document.getElementById('finish-step-text');
                const logOutput = document.getElementById('log-output');

                let progress = 62;

                const messages = [
                    'Creating process: Optical Microscopy',
                    'Creating measurement attributes',
                    'Linking samples to workflow',
                    'Creating process: Hardness Testing',
                    'Creating process: Tensile Testing',
                    'Finalizing import summary',
                    'Import complete'
                ];

                let messageIndex = 0;

                const appendLog = (level, message) => {
                    const now = new Date();
                    const time = now.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });

                    const className = level === 'OK' ? 'mc-log-success' : 'mc-log-info';

                    const line = document.createElement('div');
                    line.className = 'mc-log-line';
                    line.innerHTML = `<span class="mc-log-time">${time}</span> <span class="${className}">${level}</span> ${message}`;

                    logOutput.appendChild(line);
                    logOutput.scrollTop = logOutput.scrollHeight;
                };

                const interval = window.setInterval(() => {
                    progress = Math.min(progress + Math.floor(Math.random() * 8) + 4, 100);

                    progressBar.style.width = `${progress}%`;
                    progressLabel.textContent = `${progress}%`;

                    const message = messages[Math.min(messageIndex, messages.length - 1)];
                    liveStatusText.textContent = message;
                    processStepText.textContent = message;
                    appendLog(progress >= 100 ? 'OK' : 'INFO', message);

                    messageIndex++;

                    if (progress >= 100) {
                        window.clearInterval(interval);

                        progressBar.classList.remove('progress-bar-animated');
                        progressBar.classList.remove('progress-bar-striped');
                        progressBar.classList.add('bg-success');

                        statusBadge.className = 'badge text-bg-success';
                        statusBadge.textContent = 'Completed';

                        liveDot.className = 'mc-status-dot mc-status-dot-success';
                        liveStatusText.textContent = 'Import completed successfully';

                        processStepIcon.className = 'mc-step-icon mc-step-icon-success';
                        processStepIcon.innerHTML = '<i class="fas fa-check"></i>';
                        processStepText.textContent = 'All process records created.';

                        finishStepIcon.className = 'mc-step-icon mc-step-icon-success';
                        finishStepIcon.innerHTML = '<i class="fas fa-check"></i>';
                        finishStepText.textContent = 'Import summary saved.';

                        completeAlert.classList.remove('d-none');

                        appendLog('OK', 'Import completed successfully with 3 warnings and 0 errors');
                    }
                }, 1800);
            });
        </script>
    @endpush
@endsection
