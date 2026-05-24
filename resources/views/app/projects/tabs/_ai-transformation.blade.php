<div class="d-flex align-items-center mb-2 mt-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="proj-ai-transform-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#proj-ai-transform"
            aria-expanded="false"
            aria-controls="proj-ai-transform">
        <i class="fas fa-chevron-right fa-fw proj-chevron" style="transition:transform .2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Data Transformation
        </span>
        <span class="badge rounded-pill ms-1" style="font-size:.65rem; background:#f97316; color:#fff;">AI</span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<div class="collapse mb-1" id="proj-ai-transform"
     data-mc-collapse-key="{{$projKey}}_ai_transform">
    <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem; overflow:hidden;">
        <div class="card-header d-flex align-items-center gap-2 py-2 px-3"
             style="background:linear-gradient(135deg,#f97316 0%,#c2410c 100%); border:none;">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:30px;height:30px;background:rgba(255,255,255,.2); flex-shrink:0;">
                <i class="fas fa-random" style="color:#fff; font-size:.75rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold text-white" style="font-size:.85rem; line-height:1.1;">
                    AI Data Transformation Studio
                </div>
                <div style="font-size:.7rem; color:rgba(255,255,255,.75);">
                    Convert files, paste data, or generate Materials Commons workflows with AI-assisted enrichment
                </div>
            </div>
            <span class="d-flex align-items-center gap-1" style="font-size:.7rem; color:rgba(255,255,255,.85);">
                <span class="rounded-circle"
                      style="width:7px;height:7px;background:#4ade80;display:inline-block;"></span>
                Prototype
            </span>
        </div>

        <div class="card-body p-3 background-white">
            <div class="alert mb-3"
                 style="background:#fff7ed; border:1px solid #fed7aa; color:#9a3412; border-radius:.7rem;">
                <div class="d-flex align-items-start gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                         style="width:28px;height:28px;background:#ffedd5;">
                        <i class="fas fa-robot" style="font-size:.7rem; color:#c2410c;"></i>
                    </div>
                    <div style="font-size:.84rem; line-height:1.5;">
                        <strong>Transform incoming data into usable project structure.</strong>
                        Upload a file, paste tabular data, or describe a workflow step. I can convert formats,
                        normalize attributes, infer relationships, and prepare suggested Materials Commons records
                        for review before importing.
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6 col-lg-3">
                    <div class="card border-0 h-100" style="background:#f8fafc;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="text-muted text-uppercase"
                                         style="font-size:.68rem; letter-spacing:.04em;">
                                        Input modes
                                    </div>
                                    <div class="fw-bold text-warning" style="font-size:1.45rem; line-height:1.1;">
                                        3
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">file, paste, workflow</div>
                                </div>
                                <span class="badge rounded-pill text-bg-warning">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="card border-0 h-100" style="background:#f8fafc;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="text-muted text-uppercase"
                                         style="font-size:.68rem; letter-spacing:.04em;">
                                        Conversions
                                    </div>
                                    <div class="fw-bold text-primary" style="font-size:1.45rem; line-height:1.1;">
                                        6
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">available formats</div>
                                </div>
                                <span class="badge rounded-pill text-bg-primary">
                                    <i class="fas fa-file-export"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="card border-0 h-100" style="background:#f8fafc;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="text-muted text-uppercase"
                                         style="font-size:.68rem; letter-spacing:.04em;">
                                        Enrichment
                                    </div>
                                    <div class="fw-bold text-success" style="font-size:1.45rem; line-height:1.1;">
                                        12
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">suggested attributes</div>
                                </div>
                                <span class="badge rounded-pill text-bg-success">
                                    <i class="fas fa-magic"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="card border-0 h-100" style="background:#f8fafc;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="text-muted text-uppercase"
                                         style="font-size:.68rem; letter-spacing:.04em;">
                                        Review status
                                    </div>
                                    <div class="fw-bold text-info" style="font-size:1.45rem; line-height:1.1;">
                                        5
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">pending findings</div>
                                </div>
                                <span class="badge rounded-pill text-bg-info">
                                    <i class="fas fa-clipboard-check"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-xl-4">
                    <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width:28px;height:28px;background:#ffedd5;">
                                <i class="fas fa-file-upload" style="color:#ea580c; font-size:.7rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.86rem;">1. Transform a file</div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    Convert between data formats or generate MC Excel
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-3 p-3 mt-3 text-center"
                             style="border-style:dashed !important; border-color:#fdba74 !important; background:#fff7ed;">
                            <i class="fas fa-cloud-upload-alt mb-2" style="font-size:1.5rem; color:#ea580c;"></i>
                            <div class="fw-semibold" style="font-size:.82rem;">Drop a file here</div>
                            <div class="text-muted" style="font-size:.72rem;">
                                CSV, Excel, JSON, or Materials Commons Excel
                            </div>
                            <button type="button" class="btn btn-outline-warning btn-sm mt-2" disabled>
                                Choose file
                            </button>
                        </div>

                        <div class="mt-3">
                            <label class="form-label text-muted mb-1" style="font-size:.72rem;">Transform into</label>
                            <select class="form-select form-select-sm" disabled>
                                <option>Materials Commons Excel workflow format</option>
                                <option>JSON</option>
                                <option>CSV</option>
                                <option>Excel workbook</option>
                                <option>Sample attribute table</option>
                                <option>Process attribute table</option>
                            </select>
                        </div>

                        <div class="mt-3 p-2 rounded-3" style="background:#f8fafc;">
                            <div class="fw-semibold" style="font-size:.76rem;">Example request</div>
                            <div class="text-muted" style="font-size:.72rem; line-height:1.45;">
                                “Convert this Excel sheet into Materials Commons format and create workflow steps for
                                LPBF build, annealing, and EBSD characterization.”
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width:28px;height:28px;background:#e0f2fe;">
                                <i class="fas fa-keyboard" style="color:#0284c7; font-size:.7rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.86rem;">2. Paste and import</div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    Paste delimited data and ask AI to structure it
                                </div>
                            </div>
                        </div>

                        <textarea class="form-control mt-3"
                                  rows="7"
                                  style="resize:none; font-size:.76rem; font-family:monospace; border-color:#e5e7eb;"
                                  disabled>sample_id | material | temp_C | hold_hr | characterization
Ti64-B17-Z03 | Ti-6Al-4V | 650 | 2 | EBSD
Ti64-B17-Z04 | Ti-6Al-4V | 700 | 4 | SEM
Ti64-B17-Z05 | Ti-6Al-4V | 750 | 8 | XRD</textarea>

                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <label class="form-label text-muted mb-1" style="font-size:.72rem;">Separator</label>
                                <select class="form-select form-select-sm" disabled>
                                    <option>Auto-detect</option>
                                    <option>Comma</option>
                                    <option>Tab</option>
                                    <option>Pipe |</option>
                                    <option>Semicolon</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-muted mb-1" style="font-size:.72rem;">Import as</label>
                                <select class="form-select form-select-sm" disabled>
                                    <option>Samples + attributes</option>
                                    <option>Process measurements</option>
                                    <option>Workflow steps</option>
                                    <option>Dataset metadata</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3 p-2 rounded-3" style="background:#f8fafc;">
                            <div class="fw-semibold" style="font-size:.76rem;">AI interpretation</div>
                            <div class="text-muted" style="font-size:.72rem; line-height:1.45;">
                                Detected pipe-separated table with sample IDs, heat treatment conditions, and
                                characterization methods.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width:28px;height:28px;background:#ede9fe;">
                                <i class="fas fa-project-diagram" style="color:#7c3aed; font-size:.7rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.86rem;">3. Build workflow steps</div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    Name a step and provide attributes
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label text-muted mb-1" style="font-size:.72rem;">Workflow step name</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   value="Stress relief anneal"
                                   disabled>
                        </div>

                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <label class="form-label text-muted mb-1" style="font-size:.72rem;">Attribute</label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       value="temperature"
                                       disabled>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-muted mb-1" style="font-size:.72rem;">Value</label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       value="650°C"
                                       disabled>
                            </div>
                            <div class="col-6">
                                <input type="text"
                                       class="form-control form-control-sm"
                                       value="hold time"
                                       disabled>
                            </div>
                            <div class="col-6">
                                <input type="text"
                                       class="form-control form-control-sm"
                                       value="2 hr"
                                       disabled>
                            </div>
                            <div class="col-6">
                                <input type="text"
                                       class="form-control form-control-sm"
                                       value="cooling"
                                       disabled>
                            </div>
                            <div class="col-6">
                                <input type="text"
                                       class="form-control form-control-sm"
                                       value="air cooled"
                                       disabled>
                            </div>
                        </div>

                        <div class="mt-3 p-2 rounded-3" style="background:#f8fafc;">
                            <div class="fw-semibold" style="font-size:.76rem;">Generated structure</div>
                            <div class="text-muted" style="font-size:.72rem; line-height:1.45;">
                                Create a workflow activity linked after
                                <strong>AM Build 17</strong>, classified as
                                <strong>heat treatment → annealing</strong>.
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mt-3" disabled>
                            <i class="fas fa-plus me-1"></i>
                            Add another step
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-12 col-lg-5">
                    <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <div class="fw-semibold" style="font-size:.86rem;">Transformation preview</div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    Example output from pasted data + workflow hints
                                </div>
                            </div>
                            <span class="badge rounded-pill"
                                  style="background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;">
                                MC Excel
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0" style="font-size:.74rem;">
                                <thead class="table-light">
                                <tr>
                                    <th>Sheet</th>
                                    <th>Record</th>
                                    <th>Created from</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="fw-semibold">Samples</td>
                                    <td>Ti64-B17-Z03</td>
                                    <td>pasted table</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Processes</td>
                                    <td>Stress relief anneal</td>
                                    <td>workflow step form</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Attributes</td>
                                    <td>temperature = 650°C</td>
                                    <td>attribute extraction</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Files</td>
                                    <td>HT_650C_2hr_aircool.csv</td>
                                    <td>uploaded file</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Relationships</td>
                                    <td>sample → process → EBSD</td>
                                    <td>semantic inference</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-warning btn-sm" disabled>
                                <i class="fas fa-download me-1"></i>
                                Download preview
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="fas fa-eye me-1"></i>
                                View workbook
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-7">
                    <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <div class="fw-semibold" style="font-size:.86rem;">
                                    AI enhancements found during transformation
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    Confirm semantic mappings, normalized attributes, and inferred links
                                </div>
                            </div>
                            <span class="badge rounded-pill"
                                  style="background:#ecfdf5; color:#047857; border:1px solid #bbf7d0;">
                                Review required
                            </span>
                        </div>

                        <div class="table-responsive border rounded-3" style="border-color:#e5e7eb !important;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr style="font-size:.75rem;">
                                    <th>Enhancement</th>
                                    <th>Suggestion</th>
                                    <th>Confidence</th>
                                    <th>Review</th>
                                </tr>
                                </thead>
                                <tbody style="font-size:.76rem;">
                                <tr>
                                    <td>
                                        <div class="fw-semibold">Normalize process term</div>
                                        <div class="text-muted" style="font-size:.68rem;">
                                            semantic mapping
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill"
                                              style="background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd;">
                                            heat treatment
                                        </span>
                                        <i class="fas fa-arrow-right text-muted mx-1" style="font-size:.65rem;"></i>
                                        <span class="badge rounded-pill"
                                              style="background:#ecfdf5; color:#047857; border:1px solid #a7f3d0;">
                                            annealing
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">94%</div>
                                        <div class="progress mt-1" style="height:4px;">
                                            <div class="progress-bar bg-success" style="width:94%;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-success" disabled>Accept</button>
                                            <button type="button" class="btn btn-outline-secondary" disabled>Edit</button>
                                            <button type="button" class="btn btn-outline-danger" disabled>Reject</button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="fw-semibold">Infer build process</div>
                                        <div class="text-muted" style="font-size:.68rem;">
                                            workflow relationship
                                        </div>
                                    </td>
                                    <td>
                                        Link samples
                                        <strong>Ti64-B17-Z03–Z05</strong>
                                        to
                                        <span class="badge rounded-pill"
                                              style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                                            laser powder bed fusion
                                        </span>
                                        build step
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">91%</div>
                                        <div class="progress mt-1" style="height:4px;">
                                            <div class="progress-bar bg-success" style="width:91%;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-success" disabled>Accept</button>
                                            <button type="button" class="btn btn-outline-secondary" disabled>Edit</button>
                                            <button type="button" class="btn btn-outline-danger" disabled>Reject</button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="fw-semibold">Add characterization meaning</div>
                                        <div class="text-muted" style="font-size:.68rem;">
                                            acronym expansion
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill"
                                              style="background:#ecfeff; color:#0e7490; border:1px solid #a5f3fc;">
                                            EBSD
                                        </span>
                                        =
                                        <span class="badge rounded-pill"
                                              style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0;">
                                            electron backscatter diffraction
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">98%</div>
                                        <div class="progress mt-1" style="height:4px;">
                                            <div class="progress-bar bg-success" style="width:98%;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-success" disabled>Accept</button>
                                            <button type="button" class="btn btn-outline-secondary" disabled>Edit</button>
                                            <button type="button" class="btn btn-outline-danger" disabled>Reject</button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="fw-semibold">Suggest research concept</div>
                                        <div class="text-muted" style="font-size:.68rem;">
                                            semantic tag
                                        </div>
                                    </td>
                                    <td>
                                        Add tag
                                        <span class="badge rounded-pill"
                                              style="background:#f5f3ff; color:#6d28d9; border:1px solid #ddd6fe;">
                                            microstructural evolution
                                        </span>
                                        to generated workflow
                                    </td>
                                    <td>
                                        <div class="fw-bold text-warning">76%</div>
                                        <div class="progress mt-1" style="height:4px;">
                                            <div class="progress-bar bg-warning" style="width:76%;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-success" disabled>Accept</button>
                                            <button type="button" class="btn btn-outline-secondary" disabled>Edit</button>
                                            <button type="button" class="btn btn-outline-danger" disabled>Reject</button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="fw-semibold">Create derived measurement table</div>
                                        <div class="text-muted" style="font-size:.68rem;">
                                            possible import
                                        </div>
                                    </td>
                                    <td>
                                        Convert pasted temperature/hold-time rows into process attributes and
                                        sample-level treatment history
                                    </td>
                                    <td>
                                        <div class="fw-bold text-warning">72%</div>
                                        <div class="progress mt-1" style="height:4px;">
                                            <div class="progress-bar bg-warning" style="width:72%;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-success" disabled>Accept</button>
                                            <button type="button" class="btn btn-outline-secondary" disabled>Edit</button>
                                            <button type="button" class="btn btn-outline-danger" disabled>Reject</button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-success btn-sm" disabled>
                                <i class="fas fa-check me-1"></i>
                                Accept and import
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="fas fa-edit me-1"></i>
                                Edit transformation
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                <i class="fas fa-save me-1"></i>
                                Save as reusable transform
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" disabled>
                                <i class="fas fa-times me-1"></i>
                                Reject suggestions
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 rounded-3 p-3"
                 style="background:linear-gradient(135deg,#f8fafc 0%,#fff7ed 100%); border:1px solid #fed7aa;">
                <div class="d-flex align-items-start gap-2">
                    <i class="fas fa-lightbulb mt-1" style="color:#ea580c;"></i>
                    <div>
                        <div class="fw-semibold" style="font-size:.82rem;">Prototype concept</div>
                        <div class="text-muted" style="font-size:.76rem; line-height:1.45;">
                            AI transformation is more than file conversion. It can turn informal data into structured
                            Materials Commons records, generate workflow steps, normalize materials science terminology,
                            infer sample/process/file relationships, and ask users to approve each enhancement before
                            importing.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-3 pb-3 background-white">
            <div class="d-flex flex-wrap gap-2" style="font-size:.75rem;">
                <span class="text-muted me-1" style="line-height:1.9;">Transformation targets:</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">CSV ↔ JSON</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">Excel ↔ JSON</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">MC Excel workflow</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">pasted tabular data</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">sample attributes</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">process steps</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">workflow relationships</span>
            </div>
        </div>
    </div>
</div>
