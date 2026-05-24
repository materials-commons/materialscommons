<div class="d-flex align-items-center mb-2 mt-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="proj-ai-data-extraction-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#proj-ai-data-extraction"
            aria-expanded="false"
            aria-controls="proj-ai-data-extraction">
        <i class="fas fa-chevron-right fa-fw proj-chevron" style="transition:transform .2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Data Extraction Review
        </span>
        <span class="badge rounded-pill ms-1" style="font-size:.65rem; background:#10b981; color:#fff;">AI</span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<div class="collapse mb-1" id="proj-ai-data-extraction"
     data-mc-collapse-key="{{$projKey}}_ai_data_extraction">
    <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem; overflow:hidden;">
        <div class="card-header d-flex align-items-center gap-2 py-2 px-3"
             style="background:linear-gradient(135deg,#10b981 0%,#047857 100%); border:none;">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:30px;height:30px;background:rgba(255,255,255,.2); flex-shrink:0;">
                <i class="fas fa-magic" style="color:#fff; font-size:.75rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold text-white" style="font-size:.85rem; line-height:1.1;">
                    AI Data Extraction Review
                </div>
                <div style="font-size:.7rem; color:rgba(255,255,255,.75);">
                    Review attributes, sample links, and semantic concepts extracted from new project activity
                </div>
            </div>
            <span class="d-flex align-items-center gap-1" style="font-size:.7rem; color:rgba(255,255,255,.85);">
                <span class="rounded-circle"
                      style="width:7px;height:7px;background:#4ade80;display:inline-block;"></span>
                Ready for review
            </span>
        </div>

        <div class="card-body p-3 background-white">
            <div class="alert mb-3"
                 style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; border-radius:.7rem;">
                <div class="d-flex align-items-start gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                         style="width:28px;height:28px;background:#d1fae5;">
                        <i class="fas fa-robot" style="font-size:.7rem; color:#047857;"></i>
                    </div>
                    <div style="font-size:.84rem; line-height:1.5;">
                        <strong>I noticed you uploaded 100 EBSD images last night.</strong>
                        I extracted likely attributes from file names, image metadata, and related project records.
                        These appear to be associated with sample
                        <strong>Ti64-B17-Z03</strong> and the
                        <strong>laser powder bed fusion</strong> build study.
                        Please review the findings below.
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
                                        Processed
                                    </div>
                                    <div class="fw-bold text-success" style="font-size:1.45rem; line-height:1.1;">
                                        100
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">new EBSD images</div>
                                </div>
                                <span class="badge rounded-pill text-bg-success">
                                    <i class="fas fa-images"></i>
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
                                        Attributes
                                    </div>
                                    <div class="fw-bold text-primary" style="font-size:1.45rem; line-height:1.1;">
                                        27
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">candidate values</div>
                                </div>
                                <span class="badge rounded-pill text-bg-primary">
                                    <i class="fas fa-tags"></i>
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
                                        Relationships
                                    </div>
                                    <div class="fw-bold text-info" style="font-size:1.45rem; line-height:1.1;">
                                        4
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">sample/study links</div>
                                </div>
                                <span class="badge rounded-pill text-bg-info">
                                    <i class="fas fa-link"></i>
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
                                        Confidence
                                    </div>
                                    <div class="fw-bold text-teal" style="font-size:1.45rem; line-height:1.1;">
                                        91%
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">average match score</div>
                                </div>
                                <span class="badge rounded-pill text-bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-pills mb-3" style="font-size:.78rem;">
                <li class="nav-item">
                    <button class="nav-link active py-1 px-3"
                            type="button"
                            style="border-radius:999px;">
                        New findings
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link disabled py-1 px-3"
                            type="button"
                            style="border-radius:999px;">
                        Accepted
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link disabled py-1 px-3"
                            type="button"
                            style="border-radius:999px;">
                        Needs review
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link disabled py-1 px-3"
                            type="button"
                            style="border-radius:999px;">
                        Rejected
                    </button>
                </li>
            </ul>

            <div class="table-responsive border rounded-3" style="border-color:#e5e7eb !important;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr style="font-size:.76rem;">
                        <th style="width:18%;">Finding</th>
                        <th style="width:20%;">Extracted value</th>
                        <th style="width:18%;">Source</th>
                        <th style="width:16%;">Suggested link</th>
                        <th style="width:12%;">Confidence</th>
                        <th style="width:16%;">Review</th>
                    </tr>
                    </thead>
                    <tbody style="font-size:.78rem;">
                    <tr>
                        <td>
                            <div class="fw-semibold">Imaging method</div>
                            <div class="text-muted" style="font-size:.68rem;">semantic classification</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background:#ecfeff; color:#0e7490; border:1px solid #a5f3fc;">
                                EBSD
                            </span>
                            <span class="badge rounded-pill"
                                  style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0;">
                                electron backscatter diffraction
                            </span>
                        </td>
                        <td>
                            <div>100 image files</div>
                            <div class="text-muted" style="font-size:.68rem;">*.ang, *.ctf, *.tif</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Study: Grain mapping</div>
                            <div class="text-muted" style="font-size:.68rem;">microstructural evolution</div>
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
                            <div class="fw-semibold">Related sample</div>
                            <div class="text-muted" style="font-size:.68rem;">entity relationship</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe;">
                                Ti64-B17-Z03
                            </span>
                        </td>
                        <td>
                            <div>File path + upload batch</div>
                            <div class="text-muted" style="font-size:.68rem;">/Build17/Z03/EBSD/</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Sample: Ti64-B17-Z03</div>
                            <div class="text-muted" style="font-size:.68rem;">LPBF build coupon</div>
                        </td>
                        <td>
                            <div class="fw-bold text-success">95%</div>
                            <div class="progress mt-1" style="height:4px;">
                                <div class="progress-bar bg-success" style="width:95%;"></div>
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
                            <div class="fw-semibold">Build process</div>
                            <div class="text-muted" style="font-size:.68rem;">semantic match</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                                laser powder bed fusion
                            </span>
                            <span class="badge rounded-pill"
                                  style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0;">
                                LPBF
                            </span>
                        </td>
                        <td>
                            <div>Updated process form</div>
                            <div class="text-muted" style="font-size:.68rem;">Build 17 metadata</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Activity: AM Build 17</div>
                            <div class="text-muted" style="font-size:.68rem;">additive manufacturing</div>
                        </td>
                        <td>
                            <div class="fw-bold text-success">93%</div>
                            <div class="progress mt-1" style="height:4px;">
                                <div class="progress-bar bg-success" style="width:93%;"></div>
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
                            <div class="fw-semibold">Heat treatment</div>
                            <div class="text-muted" style="font-size:.68rem;">attribute extraction</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd;">
                                annealing
                            </span>
                            <span class="badge rounded-pill"
                                  style="background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;">
                                650°C
                            </span>
                            <span class="badge rounded-pill"
                                  style="background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;">
                                2 hr
                            </span>
                        </td>
                        <td>
                            <div>HT_650C_2hr_aircool.csv</div>
                            <div class="text-muted" style="font-size:.68rem;">process log</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Activity: Stress relief anneal</div>
                            <div class="text-muted" style="font-size:.68rem;">thermal treatment</div>
                        </td>
                        <td>
                            <div class="fw-bold text-success">90%</div>
                            <div class="progress mt-1" style="height:4px;">
                                <div class="progress-bar bg-success" style="width:90%;"></div>
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
                            <div class="fw-semibold">Microstructure result</div>
                            <div class="text-muted" style="font-size:.68rem;">meaning extraction</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background:#f5f3ff; color:#6d28d9; border:1px solid #ddd6fe;">
                                grain growth
                            </span>
                            <span class="badge rounded-pill"
                                  style="background:#f5f3ff; color:#6d28d9; border:1px solid #ddd6fe;">
                                precipitate coarsening
                            </span>
                        </td>
                        <td>
                            <div>aged_24h_SEM_precipitates.tif</div>
                            <div class="text-muted" style="font-size:.68rem;">image name + notes</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Study: Aging response</div>
                            <div class="text-muted" style="font-size:.68rem;">microstructural evolution</div>
                        </td>
                        <td>
                            <div class="fw-bold text-warning">78%</div>
                            <div class="progress mt-1" style="height:4px;">
                                <div class="progress-bar bg-warning" style="width:78%;"></div>
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
                            <div class="fw-semibold">Possible scan setting</div>
                            <div class="text-muted" style="font-size:.68rem;">needs review</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background:#fffbeb; color:#b45309; border:1px solid #fde68a;">
                                step size: 0.5 μm
                            </span>
                        </td>
                        <td>
                            <div>EBSD header metadata</div>
                            <div class="text-muted" style="font-size:.68rem;">partial extraction</div>
                        </td>
                        <td>
                            <div class="fw-semibold">Image attribute</div>
                            <div class="text-muted" style="font-size:.68rem;">candidate value</div>
                        </td>
                        <td>
                            <div class="fw-bold text-warning">71%</div>
                            <div class="progress mt-1" style="height:4px;">
                                <div class="progress-bar bg-warning" style="width:71%;"></div>
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

            <div class="row g-3 mt-3">
                <div class="col-12 col-lg-5">
                    <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-sitemap text-info me-2"></i>
                            <div class="fw-semibold" style="font-size:.86rem;">Proposed relationship graph</div>
                        </div>

                        <div class="d-flex flex-column gap-2" style="font-size:.78rem;">
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                 style="background:#f8fafc;">
                                <span>
                                    <i class="fas fa-cube text-secondary me-1"></i>
                                    Sample Ti64-B17-Z03
                                </span>
                                <span class="text-muted">is part of</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                 style="background:#f8fafc;">
                                <span>
                                    <i class="fas fa-industry text-primary me-1"></i>
                                    AM Build 17
                                </span>
                                <span class="text-muted">produced by LPBF</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                 style="background:#f8fafc;">
                                <span>
                                    <i class="fas fa-temperature-high text-danger me-1"></i>
                                    Stress relief anneal
                                </span>
                                <span class="text-muted">followed build</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                 style="background:#f8fafc;">
                                <span>
                                    <i class="fas fa-images text-success me-1"></i>
                                    100 EBSD images
                                </span>
                                <span class="text-muted">characterize sample</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-7">
                    <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <div class="fw-semibold" style="font-size:.86rem;">
                                    Suggested updates after acceptance
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    These changes would be applied to project metadata after user approval
                                </div>
                            </div>
                            <span class="badge rounded-pill"
                                  style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">
                                Preview
                            </span>
                        </div>

                        <div class="row g-2" style="font-size:.78rem;">
                            <div class="col-12 col-md-6">
                                <div class="p-2 rounded-3" style="background:#f8fafc;">
                                    <div class="text-muted" style="font-size:.68rem;">Add sample attributes</div>
                                    <div class="fw-semibold">material = Ti-6Al-4V</div>
                                    <div class="fw-semibold">build_id = B17</div>
                                    <div class="fw-semibold">location = Z03</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="p-2 rounded-3" style="background:#f8fafc;">
                                    <div class="text-muted" style="font-size:.68rem;">Add process attributes</div>
                                    <div class="fw-semibold">process = laser powder bed fusion</div>
                                    <div class="fw-semibold">heat treatment = annealing</div>
                                    <div class="fw-semibold">temperature = 650°C</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="p-2 rounded-3" style="background:#f8fafc;">
                                    <div class="text-muted" style="font-size:.68rem;">Tag uploaded files</div>
                                    <div class="fw-semibold">EBSD</div>
                                    <div class="fw-semibold">grain growth</div>
                                    <div class="fw-semibold">microstructural evolution</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="p-2 rounded-3" style="background:#f8fafc;">
                                    <div class="text-muted" style="font-size:.68rem;">Create links</div>
                                    <div class="fw-semibold">files → sample Ti64-B17-Z03</div>
                                    <div class="fw-semibold">sample → AM Build 17</div>
                                    <div class="fw-semibold">images → Grain mapping study</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-success btn-sm" disabled>
                                <i class="fas fa-check me-1"></i>
                                Accept all high-confidence findings
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="fas fa-edit me-1"></i>
                                Review one-by-one
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" disabled>
                                <i class="fas fa-times me-1"></i>
                                Reject batch
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 rounded-3 p-3"
                 style="background:linear-gradient(135deg,#f8fafc 0%,#ecfdf5 100%); border:1px solid #bbf7d0;">
                <div class="d-flex align-items-start gap-2">
                    <i class="fas fa-lightbulb mt-1" style="color:#047857;"></i>
                    <div>
                        <div class="fw-semibold" style="font-size:.82rem;">Prototype concept</div>
                        <div class="text-muted" style="font-size:.76rem; line-height:1.45;">
                            Every upload or form update can trigger background AI extraction. Materials Commons can
                            identify attributes, normalize terminology, infer sample/process relationships, and ask the
                            user to confirm changes before anything is written back to the project record.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-3 pb-3 background-white">
            <div class="d-flex flex-wrap gap-2" style="font-size:.75rem;">
                <span class="text-muted me-1" style="line-height:1.9;">Extraction targets:</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">file metadata</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">sample attributes</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">process parameters</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">semantic tags</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">entity relationships</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">dataset readiness</span>
            </div>
        </div>
    </div>
</div>
