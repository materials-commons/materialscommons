@props([
    'names' => [],
    'counts' => [],
    'urls' => [],
])

@if(count($names) > 0)
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-download me-1"></i> Most Downloaded Datasets
                </h6>

                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Owned &amp; included — click to open
                </p>

                <div id="chart-author-top-downloaded"
                     style="height:{{ min(60 + count($names) * 26, 420) }}px;"></div>
            </div>
        </div>
    </div>
@endif
