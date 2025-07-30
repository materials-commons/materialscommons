<div class="card" style="border-color: #b3c2d9">
    <div class="card-body inner-card">
        <h5 class="card-title"><strong>Studies</strong></h5>
        <hr/>
        <p class="card-text">
            Document and organize your experimental or computational research with Studies. Track processes,
            parameters, measurements, and results, all linked to your project files.
        </p>

        <div class="mt-4">
            <h6 class="mb-3"><i class="fas fa-lightbulb mr-2"></i>Key Features</h6>
            <ul class="list-unstyled features-list">
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    Track experimental processes and computational workflows
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    Record parameters, measurements, and results
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    Automatic linking to related project files
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    Easy data import from spreadsheets
                </li>
            </ul>
        </div>

        <div class="mt-4">
            <h6 class="mb-3">Quick Actions</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{route('projects.experiments.index', [$project])}}"
                   class="btn btn-outline-primary btn-sm mr-2 mb-2">
                    <i class="fas fa-flask mr-1"></i> View Studies
                </a>
                <a href="{{route('projects.experiments.create', [$project])}}"
                   class="btn btn-outline-primary btn-sm mr-2 mb-2">
                    <i class="fas fa-plus mr-1"></i> Create Study
                </a>
            </div>
        </div>

        <div class="mt-4">
            <h6 class="mb-3">Study Creation Methods</h6>
            <div class="transfer-options">
                <div class="transfer-option mb-3">
                    <h6 class="text-muted mb-2">Spreadsheet Import</h6>
                    <p class="small mb-2">Quickly create studies from annotated spreadsheets - recommended for most
                        users</p>
                    <div class="d-flex flex-wrap">
                        <a href="{{route('projects.experiments.create', [$project])}}" class="text-primary mr-3">
                            Import Spreadsheet <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="https://materialscommons.org/docs/docs/reference/spreadsheets/#overview"
                           target="_blank" class="text-primary">
                            Annotation Guide <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="transfer-option">
                    <h6 class="text-muted mb-2">API Integration</h6>
                    <p class="small mb-2">For automated study creation and management</p>
                    <a href="https://materials-commons.github.io/materials-commons-api/html/manual/experiments.html"
                       target="_blank" class="text-primary">
                        API Documentation <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
