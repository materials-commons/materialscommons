<div class="card" style="border-color: #b3c2d9">
    <div class="card-body inner-card">
        <h5 class="card-title"><strong>Files</strong></h5>
        <hr/>
        <p class="card-text">
            Securely store, version, and share your project files. Preview supported files directly in the browser,
            including images with thumbnails.
        </p>

        <div class="mt-4">
            <h6 class="mb-3"><i class="fas fa-lightbulb me-2"></i>Key Features</h6>
            <ul class="list-unstyled features-list">
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Automatic file versioning and backup
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Built-in file preview and image thumbnails
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Secure file sharing with project collaborators
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Multiple transfer methods (Web, Globus, CLI, API)
                </li>
            </ul>
        </div>


        <div class="mt-4">
            <h6 class="mb-3">Quick Actions</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{route('projects.folders.index', [$project])}}"
                   class="btn btn-outline-primary btn-sm me-2 mb-2">
                    <i class="fas fa-folder-open me-1"></i> Browse Files
                </a>
                <a href="{{route('projects.upload-files', [$project])}}"
                   class="btn btn-outline-primary btn-sm me-2 mb-2">
                    <i class="fas fa-upload me-1"></i> Upload Files
                </a>
            </div>
        </div>

        <div class="mt-4">
            <h6 class="mb-3">Transfer Methods</h6>
            <div class="transfer-options">
                <div class="transfer-option mb-3">
                    <h6 class="text-muted mb-2">Website Transfer</h6>
                    <p class="small mb-2">Best for small files and quick uploads</p>
                    <a href="{{route('projects.upload-files', [$project])}}" class="text-primary">
                        Start web upload <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="transfer-option mb-3">
                    <h6 class="text-muted mb-2">Globus Transfer</h6>
                    <p class="small mb-2">Recommended for large files and datasets</p>
                    <div class="d-flex flex-wrap">
                        <a href="{{route('projects.globus.uploads.index', [$project])}}" class="text-primary me-3">
                            Upload via Globus <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        <a href="{{route('projects.globus.downloads.index', [$project])}}" class="text-primary">
                            Download via Globus <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="transfer-option">
                    <h6 class="text-muted mb-2">Advanced Options</h6>
                    <p class="small mb-2">For automation and scripting needs</p>
                    <div class="d-flex flex-wrap">
                        <a href="https://materials-commons.github.io/materials-commons-cli/html/manual/up_down_globus.html"
                           target="_blank" class="text-primary me-3">
                            CLI Guide <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                        <a href="https://materials-commons.github.io/materials-commons-api/html/manual/file_upload_download.html"
                           target="_blank" class="text-primary">
                            API Guide <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
