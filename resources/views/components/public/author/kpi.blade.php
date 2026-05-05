@props([
    'ownedCount' => 0,
    'includedCount' => 0,
    'totalViews' => 0,
    'totalDownloads' => 0,
    'paperCount' => 0,
    'coauthorCount' => 0,
])

{{-- ══ KPI strip ════════════════════════════════════════════════════════════════ --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-sm-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Owned</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($ownedCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">datasets</div>
        </div>
    </div>

    <div class="col-6 col-sm-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Included In</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($includedCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">datasets</div>
        </div>
    </div>

    <div class="col-6 col-sm-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Views</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($totalViews) }}</div>
            <div class="text-muted" style="font-size:.65rem;">on owned</div>
        </div>
    </div>

    <div class="col-6 col-sm-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Downloads</div>
            <div class="fw-bold fs-5 text-warning">{{ number_format($totalDownloads) }}</div>
            <div class="text-muted" style="font-size:.65rem;">on owned</div>
        </div>
    </div>

    <div class="col-6 col-sm-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Papers</div>
            <div class="fw-bold fs-5 text-secondary">{{ number_format($paperCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">across datasets</div>
        </div>
    </div>

    <div class="col-6 col-sm-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Co-authors</div>
            <div class="fw-bold fs-5 text-danger">{{ number_format($coauthorCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">unique</div>
        </div>
    </div>
</div>
