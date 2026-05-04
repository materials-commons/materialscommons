<div class="row g-2 mb-3">
    <div class="col-6 col-sm-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Projects</div>
            <div class="fw-bold fs-5 text-primary">{{ $projectsCount }}</div>
            <div class="text-muted" style="font-size:.65rem;">{{ $projectsSubtitle }}</div>
        </div>
    </div>

    <div class="col-6 col-sm-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Datasets</div>
            <div class="fw-bold fs-5 text-info">{{ $datasetsCount }}</div>
            <div class="text-muted" style="font-size:.65rem;">{{ $datasetsSubtitle }}</div>
        </div>
    </div>

    <div class="col-6 col-sm-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Views</div>
            <div class="fw-bold fs-5 text-success">{{ $viewsCount }}</div>
            <div class="text-muted" style="font-size:.65rem;">{{ $viewsSubtitle }}</div>
        </div>
    </div>

    <div class="col-6 col-sm-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Downloads</div>
            <div class="fw-bold fs-5 text-warning">{{ $downloadsCount }}</div>
            <div class="text-muted" style="font-size:.65rem;">{{ $downloadsSubtitle }}</div>
        </div>
    </div>

    <div class="col-6 col-sm-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Licenses</div>
            <div class="fw-bold fs-5 text-danger">{{ $missingLicensesCount }}</div>
            <div class="text-muted" style="font-size:.65rem;">missing / incomplete</div>
        </div>
    </div>

    <div class="col-6 col-sm-3 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-2">
            <div class="text-muted small">Collaborators</div>
            <div class="fw-bold fs-5 text-secondary">{{ $collaboratorsCount }}</div>
            <div class="text-muted" style="font-size:.65rem;">{{ $collaboratorsSubtitle }}</div>
        </div>
    </div>
</div>
