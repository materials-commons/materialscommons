@extends('layouts.app')

@section('pageTitle', 'Dataset Prototype')

@section('content')
    <div class="container-fluid px-3 px-xl-4 py-3">

        {{-- ═══════════════════════════════════════════════════════════════════════
             Dataset hero
             ═══════════════════════════════════════════════════════════════════════ --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius:1rem; overflow:hidden;">
            <div class="card-body p-0 background-white">
                <div class="p-4 p-xl-5"
                     style="background:linear-gradient(135deg,#f8fafc 0%,#eef2ff 55%,#eff6ff 100%); border-bottom:1px solid #e5e7eb;">
                    <div class="d-flex flex-column flex-xl-row align-items-start justify-content-between gap-4">
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                <span class="badge rounded-pill"
                                      style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0;">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Published Dataset
                                </span>
                                <span class="badge rounded-pill"
                                      style="background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe;">
                                    Materials Science
                                </span>
                                <span class="badge rounded-pill"
                                      style="background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd;">
                                    Open Data
                                </span>
                                <span class="badge rounded-pill"
                                      style="background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;">
                                    Version 2
                                </span>
                            </div>

                            <h1 class="mb-2" style="font-size:1.85rem; line-height:1.2;">
                                Ti-6Al-4V Laser Powder Bed Fusion Heat Treatment and EBSD Characterization Dataset
                            </h1>

                            <p class="text-muted mb-3" style="font-size:.98rem; line-height:1.6; max-width:980px;">
                                Process history, thermal treatment metadata, EBSD images, grain size measurements,
                                computation outputs, and workflow records for Ti-6Al-4V samples fabricated by laser
                                powder bed fusion and analyzed after stress relief annealing.
                            </p>

                            <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size:.86rem;">
                                <span>
                                    <i class="fas fa-user-edit fa-fw me-1"></i>
                                    <strong>Authors:</strong>
                                    A. Researcher, M. Scientist, J. Engineer
                                </span>
                                <span>
                                    <i class="far fa-calendar-alt fa-fw me-1"></i>
                                    <strong>Published:</strong>
                                    Mar 14, 2026
                                </span>
                                <span>
                                    <i class="fas fa-fingerprint fa-fw me-1"></i>
                                    <strong>DOI:</strong>
                                    <a href="#" class="text-decoration-none">10.13011/mc.prototype.2026.001</a>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2 flex-shrink-0" style="min-width:220px;">
                            <button type="button" class="btn btn-primary btn-sm" disabled>
                                <i class="fas fa-download me-1"></i>
                                Download Dataset
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                <i class="fas fa-quote-right me-1"></i>
                                Cite Dataset
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="fas fa-file-import me-1"></i>
                                Import to Project
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="fas fa-share-alt me-1"></i>
                                Share
                            </button>
                        </div>
                    </div>
                </div>

                {{-- KPI strip --}}
                <div class="row g-0">
                    <div class="col-6 col-md-2 border-end border-bottom border-md-bottom-0">
                        <div class="p-3 text-center">
                            <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                                Files
                            </div>
                            <div class="fw-bold text-primary" style="font-size:1.35rem;">1,284</div>
                            <div class="text-muted" style="font-size:.7rem;">18.7 GB</div>
                        </div>
                    </div>

                    <div class="col-6 col-md-2 border-end border-bottom border-md-bottom-0">
                        <div class="p-3 text-center">
                            <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                                Samples
                            </div>
                            <div class="fw-bold text-info" style="font-size:1.35rem;">42</div>
                            <div class="text-muted" style="font-size:.7rem;">Ti64 coupons</div>
                        </div>
                    </div>

                    <div class="col-6 col-md-2 border-end border-bottom border-md-bottom-0">
                        <div class="p-3 text-center">
                            <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                                Workflows
                            </div>
                            <div class="fw-bold text-secondary" style="font-size:1.35rem;">6</div>
                            <div class="text-muted" style="font-size:.7rem;">documented</div>
                        </div>
                    </div>

                    <div class="col-6 col-md-2 border-end border-bottom border-md-bottom-0">
                        <div class="p-3 text-center">
                            <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                                Computations
                            </div>
                            <div class="fw-bold" style="font-size:1.35rem; color:#ea580c;">12</div>
                            <div class="text-muted" style="font-size:.7rem;">derived outputs</div>
                        </div>
                    </div>

                    <div class="col-6 col-md-2 border-end">
                        <div class="p-3 text-center">
                            <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                                Views
                            </div>
                            <div class="fw-bold text-success" style="font-size:1.35rem;">8,421</div>
                            <div class="text-muted" style="font-size:.7rem;">all time</div>
                        </div>
                    </div>

                    <div class="col-6 col-md-2">
                        <div class="p-3 text-center">
                            <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                                Downloads
                            </div>
                            <div class="fw-bold text-warning" style="font-size:1.35rem;">1,906</div>
                            <div class="text-muted" style="font-size:.7rem;">all time</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dataset tabs --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem;">
            <div class="card-body p-2 background-white">
                <div class="d-flex flex-wrap gap-1">
                    <a href="#" class="btn btn-sm btn-primary px-3">
                        <i class="fas fa-home me-1"></i>
                        Overview
                    </a>
                    <a href="#" class="btn btn-sm btn-light border px-3">
                        <i class="fas fa-folder-open me-1"></i>
                        Files
                        <span class="badge rounded-pill bg-secondary ms-1">1,284</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-light border px-3">
                        <i class="fas fa-cube me-1"></i>
                        Samples
                        <span class="badge rounded-pill bg-secondary ms-1">42</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-light border px-3">
                        <i class="fas fa-project-diagram me-1"></i>
                        Workflows
                        <span class="badge rounded-pill bg-secondary ms-1">6</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-light border px-3">
                        <i class="fas fa-cogs me-1"></i>
                        Activities
                        <span class="badge rounded-pill bg-secondary ms-1">18</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-light border px-3">
                        <i class="fas fa-calculator me-1"></i>
                        Computations
                        <span class="badge rounded-pill bg-secondary ms-1">12</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-light border px-3">
                        <i class="fas fa-users me-1"></i>
                        Communities
                        <span class="badge rounded-pill bg-secondary ms-1">3</span>
                    </a>
                    <a href="#" class="btn btn-sm btn-light border px-3">
                        <i class="fas fa-comments me-1"></i>
                        Comments
                        <span class="badge rounded-pill bg-secondary ms-1">4</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3 align-items-start">

            {{-- ═══════════════════════════════════════════════════════════════════
                 Main content column
                 ═══════════════════════════════════════════════════════════════════ --}}
            <div class="col-12 col-xl-8">

                {{-- Summary --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-4 background-white">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <div class="text-muted text-uppercase fw-semibold"
                                     style="font-size:.72rem; letter-spacing:.04em;">
                                    <i class="fas fa-align-left me-1"></i>
                                    Dataset Summary
                                </div>
                                <h5 class="mb-0 mt-1">Overview</h5>
                            </div>
                            <span class="badge rounded-pill"
                                  style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">
                                Public
                            </span>
                        </div>

                        <p class="text-muted mb-3" style="line-height:1.65;">
                            This dataset captures the relationship between additive manufacturing parameters,
                            stress relief annealing, and microstructural evolution in Ti-6Al-4V. The dataset includes
                            machine build metadata, heat treatment logs, EBSD image stacks, SEM images, derived grain
                            statistics, computation outputs, and workflow descriptions linking samples to processing
                            and characterization steps.
                        </p>

                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;">
                                    <div class="text-muted text-uppercase mb-1"
                                         style="font-size:.68rem; letter-spacing:.04em;">
                                        Primary material
                                    </div>
                                    <div class="fw-semibold">Ti-6Al-4V</div>
                                    <div class="text-muted" style="font-size:.75rem;">additively manufactured alloy</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;">
                                    <div class="text-muted text-uppercase mb-1"
                                         style="font-size:.68rem; letter-spacing:.04em;">
                                        Main process
                                    </div>
                                    <div class="fw-semibold">Laser powder bed fusion</div>
                                    <div class="text-muted" style="font-size:.75rem;">LPBF build metadata included</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;">
                                    <div class="text-muted text-uppercase mb-1"
                                         style="font-size:.68rem; letter-spacing:.04em;">
                                        Characterization
                                    </div>
                                    <div class="fw-semibold">EBSD + SEM</div>
                                    <div class="text-muted" style="font-size:.75rem;">grain and precipitate analysis</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Dataset contents --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <div>
                                <div class="text-muted text-uppercase fw-semibold"
                                     style="font-size:.72rem; letter-spacing:.04em;">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Dataset Contents
                                </div>
                                <h5 class="mb-0 mt-1">What is included</h5>
                            </div>
                            <span class="badge rounded-pill"
                                  style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">
                                Overview only
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:42px;height:42px;background:#eff6ff;">
                                            <i class="fas fa-folder-open text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div class="fw-semibold">Files</div>
                                                <span class="fw-bold text-primary">1,284</span>
                                            </div>
                                            <div class="text-muted mb-2" style="font-size:.76rem;">
                                                File browsing lives in the Files tab, including the full directory tree.
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 mb-3">
                                                <span class="badge bg-light text-muted border">720 EBSD</span>
                                                <span class="badge bg-light text-muted border">214 SEM</span>
                                                <span class="badge bg-light text-muted border">96 CSV</span>
                                                <span class="badge bg-light text-muted border">18 JSON</span>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                                <i class="fas fa-folder-tree me-1"></i>
                                                Browse file tree
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:42px;height:42px;background:#ecfeff;">
                                            <i class="fas fa-cube text-info"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div class="fw-semibold">Samples</div>
                                                <span class="fw-bold text-info">42</span>
                                            </div>
                                            <div class="text-muted mb-2" style="font-size:.76rem;">
                                                LPBF Ti-6Al-4V coupons with build location, heat treatment, and characterization links.
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 mb-3">
                                                <span class="badge bg-light text-muted border">Ti64-B17-Z03</span>
                                                <span class="badge bg-light text-muted border">Ti64-B17-Z04</span>
                                                <span class="badge bg-light text-muted border">Ti64-B17-Z05</span>
                                            </div>
                                            <button type="button" class="btn btn-outline-info btn-sm" disabled>
                                                <i class="fas fa-table me-1"></i>
                                                View samples
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:42px;height:42px;background:#f5f3ff;">
                                            <i class="fas fa-project-diagram" style="color:#7c3aed;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div class="fw-semibold">Workflows</div>
                                                <span class="fw-bold" style="color:#7c3aed;">6</span>
                                            </div>
                                            <div class="text-muted mb-2" style="font-size:.76rem;">
                                                Processing and characterization workflows linking samples, files, and activities.
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 mb-3">
                                                <span class="badge bg-light text-muted border">LPBF</span>
                                                <span class="badge bg-light text-muted border">annealing</span>
                                                <span class="badge bg-light text-muted border">EBSD</span>
                                            </div>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                                <i class="fas fa-route me-1"></i>
                                                View workflows
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:42px;height:42px;background:#fff7ed;">
                                            <i class="fas fa-calculator" style="color:#ea580c;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between gap-2">
                                                <div class="fw-semibold">Computations</div>
                                                <span class="fw-bold" style="color:#ea580c;">12</span>
                                            </div>
                                            <div class="text-muted mb-2" style="font-size:.76rem;">
                                                Derived analysis including grain statistics, segmentation outputs, and orientation maps.
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 mb-3">
                                                <span class="badge bg-light text-muted border">grain size</span>
                                                <span class="badge bg-light text-muted border">segmentation</span>
                                                <span class="badge bg-light text-muted border">orientation analysis</span>
                                            </div>
                                            <button type="button" class="btn btn-outline-warning btn-sm" disabled>
                                                <i class="fas fa-code-branch me-1"></i>
                                                View computations
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Analytics --}}
                <section class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#prototype-analytics"
                                aria-expanded="true"
                                aria-controls="prototype-analytics">
                            <i class="fas fa-chevron-right fa-fw"
                               style="transition:transform .2s; font-size:.75rem; transform:rotate(90deg);"></i>
                            <span class="fw-semibold"
                                  style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
                                Analytics
                            </span>
                        </button>
                        <hr class="flex-grow-1 ms-3 my-0 opacity-25">
                    </div>

                    <div class="collapse show" id="prototype-analytics">
                        <div class="row g-3">
                            <div class="col-12 col-lg-5">
                                <div class="card border-0 shadow-sm h-100" style="border-radius:.85rem;">
                                    <div class="card-body p-3 background-white">
                                        <h6 class="text-muted mb-0">
                                            <i class="fas fa-layer-group me-1"></i>
                                            Composition
                                        </h6>
                                        <div class="text-muted mb-3" style="font-size:.72rem;">
                                            High-level dataset structure
                                        </div>

                                        <div class="d-flex flex-column gap-2" style="font-size:.8rem;">
                                            <div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Files</span>
                                                    <strong>1,284</strong>
                                                </div>
                                                <div class="progress" style="height:7px;">
                                                    <div class="progress-bar bg-primary" style="width:88%;"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Samples</span>
                                                    <strong>42</strong>
                                                </div>
                                                <div class="progress" style="height:7px;">
                                                    <div class="progress-bar bg-info" style="width:55%;"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Activities</span>
                                                    <strong>18</strong>
                                                </div>
                                                <div class="progress" style="height:7px;">
                                                    <div class="progress-bar bg-success" style="width:42%;"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Computations</span>
                                                    <strong>12</strong>
                                                </div>
                                                <div class="progress" style="height:7px;">
                                                    <div class="progress-bar bg-warning" style="width:34%;"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Workflows</span>
                                                    <strong>6</strong>
                                                </div>
                                                <div class="progress" style="height:7px;">
                                                    <div class="progress-bar bg-secondary" style="width:28%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-7">
                                <div class="card border-0 shadow-sm h-100" style="border-radius:.85rem;">
                                    <div class="card-body p-3 background-white">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                <h6 class="text-muted mb-0">
                                                    <i class="fas fa-chart-line me-1"></i>
                                                    Engagement
                                                </h6>
                                                <div class="text-muted" style="font-size:.72rem;">
                                                    Views and downloads over time
                                                </div>
                                            </div>
                                            <span class="badge rounded-pill bg-light text-muted border">
                                                last 6 months
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-end gap-2 mt-4" style="height:150px;">
                                            <div class="flex-fill text-center">
                                                <div class="rounded-top bg-primary mx-auto" style="height:52px; width:34%;"></div>
                                                <div class="rounded-top bg-success mx-auto mt-1" style="height:22px; width:34%;"></div>
                                                <div class="text-muted mt-2" style="font-size:.68rem;">Oct</div>
                                            </div>
                                            <div class="flex-fill text-center">
                                                <div class="rounded-top bg-primary mx-auto" style="height:65px; width:34%;"></div>
                                                <div class="rounded-top bg-success mx-auto mt-1" style="height:29px; width:34%;"></div>
                                                <div class="text-muted mt-2" style="font-size:.68rem;">Nov</div>
                                            </div>
                                            <div class="flex-fill text-center">
                                                <div class="rounded-top bg-primary mx-auto" style="height:74px; width:34%;"></div>
                                                <div class="rounded-top bg-success mx-auto mt-1" style="height:38px; width:34%;"></div>
                                                <div class="text-muted mt-2" style="font-size:.68rem;">Dec</div>
                                            </div>
                                            <div class="flex-fill text-center">
                                                <div class="rounded-top bg-primary mx-auto" style="height:95px; width:34%;"></div>
                                                <div class="rounded-top bg-success mx-auto mt-1" style="height:44px; width:34%;"></div>
                                                <div class="text-muted mt-2" style="font-size:.68rem;">Jan</div>
                                            </div>
                                            <div class="flex-fill text-center">
                                                <div class="rounded-top bg-primary mx-auto" style="height:118px; width:34%;"></div>
                                                <div class="rounded-top bg-success mx-auto mt-1" style="height:56px; width:34%;"></div>
                                                <div class="text-muted mt-2" style="font-size:.68rem;">Feb</div>
                                            </div>
                                            <div class="flex-fill text-center">
                                                <div class="rounded-top bg-primary mx-auto" style="height:132px; width:34%;"></div>
                                                <div class="rounded-top bg-success mx-auto mt-1" style="height:64px; width:34%;"></div>
                                                <div class="text-muted mt-2" style="font-size:.68rem;">Mar</div>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-3 mt-2 justify-content-center" style="font-size:.74rem;">
                                            <span><span class="rounded-circle bg-primary d-inline-block me-1"
                                                        style="width:8px;height:8px;"></span>Views</span>
                                            <span><span class="rounded-circle bg-success d-inline-block me-1"
                                                        style="width:8px;height:8px;"></span>Downloads</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Workflow preview --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="text-muted text-uppercase fw-semibold"
                                     style="font-size:.72rem; letter-spacing:.04em;">
                                    <i class="fas fa-project-diagram me-1"></i>
                                    Workflow Preview
                                </div>
                                <h5 class="mb-0 mt-1">Processing and characterization flow</h5>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                View workflows tab
                            </button>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8fafc;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-primary"
                                     style="width:36px;height:36px;">
                                    1
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Laser powder bed fusion build</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        Build 17 · Ti-6Al-4V · scan strategy and powder metadata
                                    </div>
                                </div>
                                <span class="badge bg-light text-muted border">additive manufacturing</span>
                            </div>

                            <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8fafc;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-warning"
                                     style="width:36px;height:36px;">
                                    2
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Stress relief anneal</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        650°C · 2 hr · air cooled
                                    </div>
                                </div>
                                <span class="badge bg-light text-muted border">heat treatment</span>
                            </div>

                            <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8fafc;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-success"
                                     style="width:36px;height:36px;">
                                    3
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">EBSD characterization</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        Grain orientation maps and derived grain size distributions
                                    </div>
                                </div>
                                <span class="badge bg-light text-muted border">microstructural evolution</span>
                            </div>

                            <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8fafc;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                     style="width:36px;height:36px;background:#ea580c;">
                                    4
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Grain statistics computation</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        Segmentation, grain size distribution, and orientation analysis
                                    </div>
                                </div>
                                <span class="badge bg-light text-muted border">computation</span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Community context --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <div>
                                <div class="text-muted text-uppercase fw-semibold"
                                     style="font-size:.72rem; letter-spacing:.04em;">
                                    <i class="fas fa-users me-1"></i>
                                    Communities
                                </div>
                                <h5 class="mb-0 mt-1">Where this dataset is listed</h5>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="fas fa-external-link-alt me-1"></i>
                                View communities tab
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                             style="width:34px;height:34px;background:#0d6efd;font-size:.7rem;">
                                            AM
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.84rem;">
                                                Additive Manufacturing Benchmark Data
                                            </div>
                                            <div class="text-muted" style="font-size:.7rem;">
                                                34 datasets
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-muted mb-2" style="font-size:.74rem; line-height:1.45;">
                                        Curated collection of LPBF, directed energy deposition, and post-processing datasets.
                                    </div>
                                    <span class="badge rounded-pill"
                                          style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0;">
                                        Curated
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                             style="width:34px;height:34px;background:#7c3aed;font-size:.7rem;">
                                            Ti
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.84rem;">
                                                Titanium Alloy Microstructure
                                            </div>
                                            <div class="text-muted" style="font-size:.7rem;">
                                                19 datasets
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-muted mb-2" style="font-size:.74rem; line-height:1.45;">
                                        Datasets focused on Ti alloy processing, characterization, and microstructural evolution.
                                    </div>
                                    <span class="badge rounded-pill"
                                          style="background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe;">
                                        Listed
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                             style="width:34px;height:34px;background:#198754;font-size:.7rem;">
                                            MD
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.84rem;">
                                                Materials Data Facility Examples
                                            </div>
                                            <div class="text-muted" style="font-size:.7rem;">
                                                52 datasets
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-muted mb-2" style="font-size:.74rem; line-height:1.45;">
                                        Example datasets demonstrating reusable materials data publication patterns.
                                    </div>
                                    <span class="badge rounded-pill"
                                          style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
                                        Pending review
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Citation --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <div>
                                <div class="text-muted text-uppercase fw-semibold"
                                     style="font-size:.72rem; letter-spacing:.04em;">
                                    <i class="fas fa-quote-right me-1"></i>
                                    Citation
                                </div>
                                <h5 class="mb-0 mt-1">How to cite this dataset</h5>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="far fa-copy me-1"></i>
                                Copy citation
                            </button>
                        </div>

                        <div class="p-3 rounded-3" style="background:#f8fafc; font-size:.86rem; line-height:1.6;">
                            A. Researcher, M. Scientist, and J. Engineer,
                            <em>Ti-6Al-4V Laser Powder Bed Fusion Heat Treatment and EBSD Characterization Dataset</em>,
                            Materials Commons, 2026. DOI:
                            <a href="#" class="text-decoration-none">10.13011/mc.prototype.2026.001</a>.
                        </div>
                    </div>
                </section>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════════
                 Sidebar
                 ═══════════════════════════════════════════════════════════════════ --}}
            <div class="col-12 col-xl-4">

                {{-- Dataset details --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="text-muted text-uppercase fw-semibold mb-3"
                             style="font-size:.72rem; letter-spacing:.04em;">
                            <i class="fas fa-info-circle me-1"></i>
                            Dataset Details
                        </div>

                        <div class="d-flex flex-column gap-3" style="font-size:.84rem;">
                            <div>
                                <div class="text-muted" style="font-size:.7rem;">Publication status</div>
                                <div class="fw-semibold text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Published
                                </div>
                            </div>

                            <div>
                                <div class="text-muted" style="font-size:.7rem;">Published date</div>
                                <div class="fw-semibold">Mar 14, 2026</div>
                            </div>

                            <div>
                                <div class="text-muted" style="font-size:.7rem;">License</div>
                                <div class="fw-semibold">CC BY 4.0</div>
                            </div>

                            <div>
                                <div class="text-muted" style="font-size:.7rem;">Dataset ID</div>
                                <div>
                                    <code class="text-muted">mc-ds-10294</code>
                                </div>
                            </div>

                            <div>
                                <div class="text-muted" style="font-size:.7rem;">DOI</div>
                                <div>
                                    <a href="#" class="text-decoration-none">10.13011/mc.prototype.2026.001</a>
                                </div>
                            </div>

                            <div>
                                <div class="text-muted" style="font-size:.7rem;">Total size</div>
                                <div class="fw-semibold">18.7 GB</div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Authors --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="text-muted text-uppercase fw-semibold mb-3"
                             style="font-size:.72rem; letter-spacing:.04em;">
                            <i class="fas fa-users me-1"></i>
                            Authors
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:34px;height:34px;background:#0d6efd;font-size:.7rem;">
                                    AR
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:.84rem;">A. Researcher</div>
                                    <div class="text-muted" style="font-size:.72rem;">University of Michigan</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:34px;height:34px;background:#198754;font-size:.7rem;">
                                    MS
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:.84rem;">M. Scientist</div>
                                    <div class="text-muted" style="font-size:.72rem;">National Lab</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:34px;height:34px;background:#6f42c1;font-size:.7rem;">
                                    JE
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:.84rem;">J. Engineer</div>
                                    <div class="text-muted" style="font-size:.72rem;">Materials Institute</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Communities --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="text-muted text-uppercase fw-semibold"
                                 style="font-size:.72rem; letter-spacing:.04em;">
                                <i class="fas fa-users me-1"></i>
                                Communities
                            </div>
                            <span class="badge rounded-pill bg-light text-muted border">3</span>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <div class="p-2 rounded-3" style="background:#f8fafc;">
                                <div class="fw-semibold" style="font-size:.82rem;">
                                    Additive Manufacturing Benchmark Data
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    Curated collection · 34 datasets
                                </div>
                            </div>

                            <div class="p-2 rounded-3" style="background:#f8fafc;">
                                <div class="fw-semibold" style="font-size:.82rem;">
                                    Titanium Alloy Microstructure
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    Listed · 19 datasets
                                </div>
                            </div>

                            <div class="p-2 rounded-3" style="background:#f8fafc;">
                                <div class="fw-semibold" style="font-size:.82rem;">
                                    Materials Data Facility Examples
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    Pending review · 52 datasets
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Tags --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="text-muted text-uppercase fw-semibold mb-3"
                             style="font-size:.72rem; letter-spacing:.04em;">
                            <i class="fas fa-tags me-1"></i>
                            Topics
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="badge rounded-pill text-decoration-none"
                               style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                                additive manufacturing
                            </a>
                            <a href="#" class="badge rounded-pill text-decoration-none"
                               style="background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd;">
                                laser powder bed fusion
                            </a>
                            <a href="#" class="badge rounded-pill text-decoration-none"
                               style="background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;">
                                heat treatment
                            </a>
                            <a href="#" class="badge rounded-pill text-decoration-none"
                               style="background:#ecfdf5; color:#047857; border:1px solid #a7f3d0;">
                                annealing
                            </a>
                            <a href="#" class="badge rounded-pill text-decoration-none"
                               style="background:#f5f3ff; color:#6d28d9; border:1px solid #ddd6fe;">
                                microstructural evolution
                            </a>
                            <a href="#" class="badge rounded-pill text-decoration-none"
                               style="background:#ecfeff; color:#0e7490; border:1px solid #a5f3fc;">
                                EBSD
                            </a>
                        </div>
                    </div>
                </section>

                {{-- Funding --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="text-muted text-uppercase fw-semibold mb-3"
                             style="font-size:.72rem; letter-spacing:.04em;">
                            <i class="fas fa-hand-holding-usd me-1"></i>
                            Funding
                        </div>

                        <div class="p-2 rounded-3 mb-2" style="background:#f8fafc;">
                            <div class="fw-semibold" style="font-size:.82rem;">NSF DMREF</div>
                            <div class="text-muted" style="font-size:.72rem;">Award 2040000</div>
                        </div>

                        <div class="p-2 rounded-3" style="background:#f8fafc;">
                            <div class="fw-semibold" style="font-size:.82rem;">DOE Basic Energy Sciences</div>
                            <div class="text-muted" style="font-size:.72rem;">Materials data infrastructure</div>
                        </div>
                    </div>
                </section>

                {{-- Related resources --}}
                <section class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
                    <div class="card-body p-3 background-white">
                        <div class="text-muted text-uppercase fw-semibold mb-3"
                             style="font-size:.72rem; letter-spacing:.04em;">
                            <i class="fas fa-link me-1"></i>
                            Related Resources
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm text-start" disabled>
                                <i class="fas fa-file-alt me-1"></i>
                                Associated publication
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm text-start" disabled>
                                <i class="fas fa-database me-1"></i>
                                Related dataset: SEM aging response
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm text-start" disabled>
                                <i class="fas fa-users me-1"></i>
                                Materials Data Community
                            </button>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection
