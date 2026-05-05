@props([
    'coauthorNames' => [],
    'coauthorCounts' => [],
])

@if(count($coauthorNames) > 0)
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3 background-white">
                <h6 class="card-title text-muted mb-0">
                    <i class="fas fa-users me-1"></i> Frequent Co-authors
                </h6>

                <p class="text-muted mb-1" style="font-size:.7rem;">
                    Shared datasets count
                </p>

                <div id="chart-author-coauthors"
                     style="height:{{ min(60 + count($coauthorNames) * 26, 380) }}px;"></div>
            </div>
        </div>
    </div>
@endif
