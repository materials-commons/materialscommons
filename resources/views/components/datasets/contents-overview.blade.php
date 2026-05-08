@props([
    'dataset',
])

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
                                <span class="fw-bold text-primary">{{ number_format($dataset->files_count) }}</span>
                            </div>
                            <div class="text-muted mb-2" style="font-size:.76rem;">
                                File browsing lives in the Files tab, including the full directory tree.
                            </div>
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-light text-muted border">{{ formatBytes($dataset->total_files_size) }}</span>
                                <span class="badge bg-light text-muted border">directory structure</span>
                                <span class="badge bg-light text-muted border">downloads</span>
                            </div>
                            <a href="{{ route('public.datasets.folders.show', [$dataset, '-1']) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-folder-tree me-1"></i>
                                Browse file tree
                            </a>
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
                                <span class="fw-bold text-info">{{ number_format($dataset->entities_count) }}</span>
                            </div>
                            <div class="text-muted mb-2" style="font-size:.76rem;">
                                Dataset samples and entities with their associated attributes and relationships.
                            </div>
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-light text-muted border">sample records</span>
                                <span class="badge bg-light text-muted border">attributes</span>
                                <span class="badge bg-light text-muted border">relationships</span>
                            </div>
                            <a href="{{ route('public.datasets.entities.index', ['dataset' => $dataset]) }}"
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-table me-1"></i>
                                View samples
                            </a>
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
                                <span class="fw-bold" style="color:#7c3aed;">
                                    {{ number_format($dataset->workflows_count) }}
                                </span>
                            </div>
                            <div class="text-muted mb-2" style="font-size:.76rem;">
                                Processing and characterization workflows linking samples, files, and activities.
                            </div>
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-light text-muted border">process flow</span>
                                <span class="badge bg-light text-muted border">activities</span>
                                <span class="badge bg-light text-muted border">provenance</span>
                            </div>
                            <a href="{{ route('public.datasets.workflows.index', ['dataset' => $dataset]) }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-route me-1"></i>
                                View workflows
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 h-100" style="border-color:#e5e7eb !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;background:#eef2ff;">
                            <i class="fas fa-users" style="color:#4f46e5;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between gap-2">
                                <div class="fw-semibold">Communities</div>
                                <span class="fw-bold" style="color:#4f46e5;">
                                    {{ number_format($dataset->communities_count ?? 0) }}
                                </span>
                            </div>
                            <div class="text-muted mb-2" style="font-size:.76rem;">
                                Communities provide research context and help users discover related datasets.
                            </div>
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-light text-muted border">collections</span>
                                <span class="badge bg-light text-muted border">topics</span>
                                <span class="badge bg-light text-muted border">curation</span>
                            </div>
                            <a href="{{ route('public.datasets.communities.index', [$dataset]) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-users me-1"></i>
                                View communities
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
