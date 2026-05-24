@props([
    'coauthorNames' => [],
    'coauthorCounts' => [],
])

@if(count($coauthorNames) > 0)
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                    <div>
                        <h6 class="card-title text-muted mb-0">
                            <i class="fas fa-users me-1"></i>Frequent Co-authors
                        </h6>

                        <p class="text-muted mb-0" style="font-size:.72rem;">
                            Shared datasets count
                        </p>
                    </div>

                    <span class="badge text-bg-light border text-muted">
                        Click bars
                    </span>
                </div>

                <div id="chart-author-coauthors"
                     class="js-plotly-plot"
                     style="height:{{ min(60 + count($coauthorNames) * 26, 380) }}px; cursor:pointer;"></div>
            </div>
        </div>
    </div>
@endif
