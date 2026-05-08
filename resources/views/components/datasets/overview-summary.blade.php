@props([
    'dataset',
])

@php
    $summary = $dataset->description ?: $dataset->summary;
@endphp

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

        @if(!blank($summary))
            <div class="text-muted mb-3" style="line-height:1.65;">
                <x-markdown>{!!$summary!!}</x-markdown>
        @else
            <p class="text-muted mb-3" style="line-height:1.65;">
                No summary has been provided for this dataset.
            </p>
        @endif

        <div class="row g-2">
            <div class="col-12 col-md-4">
                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;">
                    <div class="text-muted text-uppercase mb-1"
                         style="font-size:.68rem; letter-spacing:.04em;">
                        Files
                    </div>
                    <div class="fw-semibold">{{ number_format($dataset->files_count) }} files</div>
                    <div class="text-muted" style="font-size:.75rem;">
                        {{ formatBytes($dataset->total_files_size) }} total
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;">
                    <div class="text-muted text-uppercase mb-1"
                         style="font-size:.68rem; letter-spacing:.04em;">
                        Samples
                    </div>
                    <div class="fw-semibold">{{ number_format($dataset->entities_count) }} samples</div>
                    <div class="text-muted" style="font-size:.75rem;">
                        entities included in dataset
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;">
                    <div class="text-muted text-uppercase mb-1"
                         style="font-size:.68rem; letter-spacing:.04em;">
                        Workflows
                    </div>
                    <div class="fw-semibold">{{ number_format($dataset->workflows_count) }} workflows</div>
                    <div class="text-muted" style="font-size:.75rem;">
                        documented process flow
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
