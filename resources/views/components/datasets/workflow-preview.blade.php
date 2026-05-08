@props([
    'dataset',
])

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

            @if(($dataset->workflows_count ?? 0) > 0)
                <a href="{{ route('public.datasets.workflows.index', ['dataset' => $dataset]) }}"
                   class="btn btn-outline-secondary btn-sm">
                    View workflows tab
                </a>
            @endif
        </div>

        @if(($dataset->workflows_count ?? 0) > 0)
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8fafc;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-primary"
                         style="width:36px;height:36px;">
                        1
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Dataset workflow available</div>
                        <div class="text-muted" style="font-size:.75rem;">
                            This dataset includes documented workflows connecting files, samples, and activities.
                        </div>
                    </div>
                    <span class="badge bg-light text-muted border">workflow</span>
                </div>

                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8fafc;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-success"
                         style="width:36px;height:36px;">
                        2
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Explore process relationships</div>
                        <div class="text-muted" style="font-size:.75rem;">
                            Open the Workflows tab to inspect activity order, sample lineage, and associated files.
                        </div>
                    </div>
                    <span class="badge bg-light text-muted border">provenance</span>
                </div>
            </div>
        @else
            <p class="text-muted mb-0">
                No workflows are documented for this dataset.
            </p>
        @endif
    </div>
</section>
