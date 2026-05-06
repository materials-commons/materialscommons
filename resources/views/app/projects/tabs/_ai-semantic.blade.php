<div class="d-flex align-items-center mb-2 mt-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="proj-ai-semantic-matches-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#proj-ai-semantic-matches"
            aria-expanded="false"
            aria-controls="proj-ai-semantic-matches">
        <i class="fas fa-chevron-right fa-fw proj-chevron" style="transition:transform .2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Semantic Matches
        </span>
        <span class="badge rounded-pill ms-1" style="font-size:.65rem; background:#0ea5e9; color:#fff;">AI</span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<div class="collapse mb-1" id="proj-ai-semantic-matches"
     data-mc-collapse-key="{{$projKey}}_ai_semantic_matches">
    <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem; overflow:hidden;">
        <div class="card-header d-flex align-items-center gap-2 py-2 px-3"
             style="background:linear-gradient(135deg,#0ea5e9 0%,#2563eb 100%); border:none;">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:30px;height:30px;background:rgba(255,255,255,.2); flex-shrink:0;">
                <i class="fas fa-project-diagram" style="color:#fff; font-size:.75rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold text-white" style="font-size:.85rem; line-height:1.1;">
                    AI Semantic Discovery
                </div>
                <div style="font-size:.7rem; color:rgba(255,255,255,.75);">
                    Find files, samples, studies, and process records by meaning — not just exact keywords
                </div>
            </div>
            <span class="d-flex align-items-center gap-1" style="font-size:.7rem; color:rgba(255,255,255,.85);">
                <span class="rounded-circle"
                      style="width:7px;height:7px;background:#4ade80;display:inline-block;"></span>
                Prototype
            </span>
        </div>

        <div class="card-body p-3 background-white">
            <div class="row g-3">
                <div class="col-12 col-xl-4">
                    <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width:28px;height:28px;background:#e0f2fe;">
                                <i class="fas fa-search" style="color:#0284c7; font-size:.7rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.86rem;">Query by concept</div>
                                <div class="text-muted" style="font-size:.72rem;">Example semantic searches</div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2 mt-3">
                            <button type="button"
                                    class="btn btn-sm text-start px-3 py-2"
                                    style="border:1px solid #bae6fd; background:#f0f9ff; color:#0369a1; border-radius:.65rem;"
                                    disabled>
                                <i class="fas fa-temperature-high me-2"></i>
                                heat treatment
                            </button>
                            <button type="button"
                                    class="btn btn-sm text-start px-3 py-2"
                                    style="border:1px solid #bfdbfe; background:#eff6ff; color:#1d4ed8; border-radius:.65rem;"
                                    disabled>
                                <i class="fas fa-cubes me-2"></i>
                                additive manufacturing
                            </button>
                            <button type="button"
                                    class="btn btn-sm text-start px-3 py-2"
                                    style="border:1px solid #ddd6fe; background:#f5f3ff; color:#6d28d9; border-radius:.65rem;"
                                    disabled>
                                <i class="fas fa-seedling me-2"></i>
                                microstructural evolution
                            </button>
                        </div>

                        <div class="mt-3 p-2 rounded-3" style="background:#f8fafc;">
                            <div class="text-muted" style="font-size:.74rem; line-height:1.45;">
                                AI expands each concept into related scientific terminology, then surfaces project
                                assets whose descriptions, metadata, names, and attributes are nearby in semantic
                                space.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <div class="fw-semibold" style="font-size:.9rem;">Top semantic matches</div>
                            <div class="text-muted" style="font-size:.72rem;">
                                Demonstration data showing files and database entries matched by meaning
                            </div>
                        </div>
                        <span class="badge rounded-pill"
                              style="background:#ecfeff; color:#0e7490; border:1px solid #a5f3fc;">
                            Vector similarity
                        </span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="border rounded-3 p-3" style="border-color:#e5e7eb !important;">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge rounded-pill"
                                              style="background:#f0f9ff; color:#0369a1;">heat treatment</span>
                                        <i class="fas fa-arrow-right text-muted" style="font-size:.7rem;"></i>
                                        <span class="badge rounded-pill"
                                              style="background:#ecfdf5; color:#047857;">annealing</span>
                                    </div>
                                    <div class="fw-semibold" style="font-size:.86rem;">
                                        Matched concept: annealing, thermal soak, furnace cooling
                                    </div>
                                    <div class="text-muted mt-1" style="font-size:.76rem; line-height:1.45;">
                                        The query does not appear verbatim in every result, but the model recognizes
                                        related process terms and thermal treatment metadata.
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <div class="fw-bold text-primary" style="font-size:.95rem;">94%</div>
                                    <div class="text-muted" style="font-size:.68rem;">similarity</div>
                                </div>
                            </div>

                            <div class="mt-3 d-flex flex-column gap-2">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                     style="background:#f8fafc;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-file-alt text-primary"></i>
                                        <div>
                                            <div style="font-size:.8rem;">HT_650C_2hr_aircool.csv</div>
                                            <div class="text-muted" style="font-size:.68rem;">File · Process log</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-muted border">thermal cycle</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                     style="background:#f8fafc;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-flask text-info"></i>
                                        <div>
                                            <div style="font-size:.8rem;">Study: Post-build stress relief</div>
                                            <div class="text-muted" style="font-size:.68rem;">Database entry · Study</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-muted border">anneal schedule</span>
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-3 p-3" style="border-color:#e5e7eb !important;">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge rounded-pill"
                                              style="background:#eff6ff; color:#1d4ed8;">additive manufacturing</span>
                                        <i class="fas fa-arrow-right text-muted" style="font-size:.7rem;"></i>
                                        <span class="badge rounded-pill"
                                              style="background:#ecfdf5; color:#047857;">laser powder bed fusion</span>
                                    </div>
                                    <div class="fw-semibold" style="font-size:.86rem;">
                                        Matched concept: LPBF, build plate, scan strategy, layer thickness
                                    </div>
                                    <div class="text-muted mt-1" style="font-size:.76rem; line-height:1.45;">
                                        Broader manufacturing language maps to specific process names and
                                        abbreviations used in uploaded files and structured records.
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <div class="fw-bold text-primary" style="font-size:.95rem;">91%</div>
                                    <div class="text-muted" style="font-size:.68rem;">similarity</div>
                                </div>
                            </div>

                            <div class="mt-3 d-flex flex-column gap-2">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                     style="background:#f8fafc;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-file-code text-primary"></i>
                                        <div>
                                            <div style="font-size:.8rem;">build_17_scan_parameters.json</div>
                                            <div class="text-muted" style="font-size:.68rem;">File · Machine parameters</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-muted border">LPBF</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                     style="background:#f8fafc;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-cube text-secondary"></i>
                                        <div>
                                            <div style="font-size:.8rem;">Sample: Ti64-B17-Z03</div>
                                            <div class="text-muted" style="font-size:.68rem;">Database entry · Sample</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-muted border">powder bed</span>
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-3 p-3" style="border-color:#e5e7eb !important;">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge rounded-pill"
                                              style="background:#f5f3ff; color:#6d28d9;">microstructural evolution</span>
                                        <i class="fas fa-arrow-right text-muted" style="font-size:.7rem;"></i>
                                        <span class="badge rounded-pill"
                                              style="background:#ecfdf5; color:#047857;">grain growth</span>
                                        <span class="badge rounded-pill"
                                              style="background:#ecfdf5; color:#047857;">precipitate coarsening</span>
                                    </div>
                                    <div class="fw-semibold" style="font-size:.86rem;">
                                        Matched concept: phase change, coarsening, EBSD grain statistics
                                    </div>
                                    <div class="text-muted mt-1" style="font-size:.76rem; line-height:1.45;">
                                        The model connects high-level materials science concepts to microscopy images,
                                        derived measurements, and analysis tables.
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <div class="fw-bold text-primary" style="font-size:.95rem;">88%</div>
                                    <div class="text-muted" style="font-size:.68rem;">similarity</div>
                                </div>
                            </div>

                            <div class="mt-3 d-flex flex-column gap-2">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                     style="background:#f8fafc;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-image text-primary"></i>
                                        <div>
                                            <div style="font-size:.8rem;">aged_24h_SEM_precipitates.tif</div>
                                            <div class="text-muted" style="font-size:.68rem;">File · Microscopy image</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-muted border">coarsening</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3"
                                     style="background:#f8fafc;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-table text-success"></i>
                                        <div>
                                            <div style="font-size:.8rem;">grain_size_distribution_after_aging.xlsx</div>
                                            <div class="text-muted" style="font-size:.68rem;">File · Analysis table</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-muted border">grain growth</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 rounded-3 p-3"
                         style="background:linear-gradient(135deg,#f8fafc 0%,#eef2ff 100%); border:1px solid #e0e7ff;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-lightbulb mt-1" style="color:#4f46e5;"></i>
                            <div>
                                <div class="fw-semibold" style="font-size:.82rem;">Why this is useful</div>
                                <div class="text-muted" style="font-size:.76rem; line-height:1.45;">
                                    Researchers can discover relevant project content even when different teams use
                                    different vocabulary, abbreviations, file naming conventions, or domain-specific
                                    terminology.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-3 pb-3 background-white">
            <div class="d-flex flex-wrap gap-2" style="font-size:.75rem;">
                <span class="text-muted me-1" style="line-height:1.9;">Could support:</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">semantic file search</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">metadata enrichment</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">ontology mapping</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">related studies</span>
                <span class="px-2 py-1 rounded-pill" style="background:#f1f5f9; color:#475569;">dataset recommendations</span>
            </div>
        </div>
    </div>
</div>
