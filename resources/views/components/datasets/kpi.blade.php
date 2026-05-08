@props([
    'dataset',
])

<div class="card border-0 shadow-sm mb-3" style="border-radius:.85rem; overflow:hidden;">
    <div class="row g-0">
        <div class="col-6 col-md-2 border-end border-bottom border-md-bottom-0">
            <a href="{{ route('public.datasets.folders.show', [$dataset, '-1']) }}"
               class="text-decoration-none">
                <div class="p-3 text-center h-100">
                    <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                        Files
                    </div>
                    <div class="fw-bold text-primary" style="font-size:1.35rem;">
                        {{ number_format($dataset->files_count) }}
                    </div>
                    <div class="text-muted" style="font-size:.7rem;">
                        {{ formatBytes($dataset->total_files_size) }}
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-2 border-end border-bottom border-md-bottom-0">
            <a href="{{ route('public.datasets.entities.index', ['dataset' => $dataset]) }}"
               class="text-decoration-none">
                <div class="p-3 text-center h-100">
                    <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                        Samples
                    </div>
                    <div class="fw-bold text-info" style="font-size:1.35rem;">
                        {{ number_format($dataset->entities_count) }}
                    </div>
                    <div class="text-muted" style="font-size:.7rem;">
                        in dataset
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-2 border-end border-bottom border-md-bottom-0">
            <a href="{{ route('public.datasets.workflows.index', ['dataset' => $dataset]) }}"
               class="text-decoration-none">
                <div class="p-3 text-center h-100">
                    <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                        Workflows
                    </div>
                    <div class="fw-bold text-secondary" style="font-size:1.35rem;">
                        {{ number_format($dataset->workflows_count) }}
                    </div>
                    <div class="text-muted" style="font-size:.7rem;">
                        documented
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-2 border-end border-bottom border-md-bottom-0">
            <div class="p-3 text-center h-100">
                <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                    Communities
                </div>
                <div class="fw-bold" style="font-size:1.35rem; color:#7c3aed;">
                    {{ number_format($dataset->communities_count ?? 0) }}
                </div>
                <div class="text-muted" style="font-size:.7rem;">
                    listed in
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2 border-end">
            <div class="p-3 text-center h-100">
                <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                    Views
                </div>
                <div class="fw-bold text-success" style="font-size:1.35rem;">
                    {{ number_format($dataset->views_count ?? 0) }}
                </div>
                <div class="text-muted" style="font-size:.7rem;">
                    total views
                </div>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="p-3 text-center h-100">
                <div class="text-muted text-uppercase" style="font-size:.68rem; letter-spacing:.04em;">
                    Downloads
                </div>
                <div class="fw-bold text-warning" style="font-size:1.35rem;">
                    {{ number_format($dataset->downloads_count ?? 0) }}
                </div>
                <div class="text-muted" style="font-size:.7rem;">
                    total downloads
                </div>
            </div>
        </div>
    </div>
</div>
