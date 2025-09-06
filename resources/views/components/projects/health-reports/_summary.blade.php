@props(['healthReport'])
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-{{ $healthReport->missingFiles->count() > 0 ? 'danger' : 'success' }}">
                    {{ $healthReport->missingFiles->count() }}
                </h4>
                <p class="mb-0">Missing Files</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-{{ $healthReport->oldGlobusDownloads->count() > 0 ? 'warning' : 'success' }}">
                    {{ $healthReport->oldGlobusDownloads->count() }}
                </h4>
                <p class="mb-0">Old Globus Downloads</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-{{ $healthReport->unpublishedDatasetsWithDOIs->count() > 0 ? 'warning' : 'success' }}">
                    {{ $healthReport->unpublishedDatasetsWithDOIs->count() }}
                </h4>
                <p class="mb-0">Unpublished Datasets with DOIs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-{{ $healthReport->unprocessedGlobusUploads->count() > 0 ? 'info' : 'success' }}">
                    {{ $healthReport->unprocessedGlobusUploads->count() }}
                </h4>
                <p class="mb-0">Unprocessed Globus Uploads</p>
            </div>
        </div>
    </div>
</div>
