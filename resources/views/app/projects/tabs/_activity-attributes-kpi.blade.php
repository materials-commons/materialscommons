<div class="row g-2 mb-3">
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Attributes</div>
            <div class="fw-bold fs-5 text-primary">{{ number_format($totalAttrs) }}</div>
            <div class="text-muted" style="font-size:.65rem;">in this project</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Total Values</div>
            <div class="fw-bold fs-5 text-info">{{ number_format($totalValues) }}</div>
            <div class="text-muted" style="font-size:.65rem;">across all attrs</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">With Units</div>
            <div class="fw-bold fs-5 text-success">{{ number_format($attrsWithUnits) }}</div>
            <div class="text-muted" style="font-size:.65rem;">have unit labels</div>
        </div>
    </div>
    <div class="col-6 col-sm-3">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Numeric Range</div>
            <div class="fw-bold fs-5 text-warning">{{ number_format($numericCount) }}</div>
            <div class="text-muted" style="font-size:.65rem;">have min &amp; max</div>
        </div>
    </div>
</div>
