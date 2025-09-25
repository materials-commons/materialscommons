@props(['healthReport'])
<div class="issues-detail">
    <h5><i class="fas fa-exclamation-triangle text-warning"></i> Issues Found</h5>

    @if($healthReport->missingFiles->count() > 0)
        <x-projects.health-reports.issues._missing-files :files="$healthReport->missingFiles" />
    @endif

    @if($healthReport->oldGlobusDownloads->count() > 0)
        <x-projects.health-reports.issues._old-globus-downloads :downloads="$healthReport->oldGlobusDownloads" />
    @endif

    @if($healthReport->unpublishedDatasetsWithDOIs->count() > 0)
        <x-projects.health-reports.issues._unpublished-datasets :datasets="$healthReport->unpublishedDatasetsWithDOIs" />
    @endif

    @if($healthReport->unprocessedGlobusUploads->count() > 0)
        <x-projects.health-reports.issues._unprocessed-uploads :uploads="$healthReport->unprocessedGlobusUploads" />
    @endif
</div>
