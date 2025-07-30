<div class="card" style="border-color: #b3c2d9">
    <div class="card-body inner-card">
        <h5 class="card-title"><strong>Datasets and Communities</strong></h5>
        <hr/>
        <p class="card-text">
            Share and publish your research data with DOI support. Create datasets from your studies
            and join research communities to increase visibility and collaboration.
        </p>

        <div class="mt-4">
            <h6 class="mb-3"><i class="fas fa-lightbulb mr-2"></i>Key Features</h6>
            <ul class="list-unstyled features-list">
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    DOI assignment for citation and tracking
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    Public access with flexible download options
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    Import and reuse published datasets
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    Community-based research organization
                </li>
            </ul>
        </div>

        <div class="mt-4">
            <h6 class="mb-3">Quick Actions</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{route('projects.datasets.index', [$project])}}"
                   class="btn btn-outline-primary btn-sm mr-2 mb-2">
                    <i class="fas fa-database mr-1"></i> View Datasets
                </a>
                <a href="{{route('projects.datasets.create', [$project])}}"
                   class="btn btn-outline-primary btn-sm mr-2 mb-2">
                    <i class="fas fa-plus mr-1"></i> Create Dataset
                </a>
            </div>
        </div>

        <div class="mt-4">
            <h6 class="mb-3">Publishing Options</h6>
            <div class="transfer-options">
                <div class="transfer-option mb-3">
                    <h6 class="text-muted mb-2">Dataset Management</h6>
                    <p class="small mb-2">Create and manage research datasets</p>
                    <div class="d-flex flex-wrap">
                        <a href="{{route('public.index')}}" class="text-primary mr-3">
                            Browse Published Datasets <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="https://materialscommons.org/docs/docs/publishing-data/create-dataset-website/"
                           target="_blank" class="text-primary">
                            Publishing Guide <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="transfer-option">
                    <h6 class="text-muted mb-2">Research Communities</h6>
                    <p class="small mb-2">Connect with researchers in your field</p>
                    <div class="d-flex flex-wrap">
                        <a href="{{route('communities.index')}}" class="text-primary mr-3">
                            Explore Communities <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        <a href="{{route('communities.create')}}" class="text-primary mr-3">
                            Create Community <i class="fas fa-plus ml-1"></i>
                        </a>
                        <a href="https://materialscommons.org/docs/docs/communities/"
                           target="_blank" class="text-primary">
                            Communities Guide <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
