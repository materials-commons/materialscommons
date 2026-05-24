<div class="d-flex justify-content-end align-items-center gap-2 mb-3">
    <a class="btn btn-success"
       href="{{ route('projects.datasets.show', [$project, $dataset]) }}">
        <i class="fas fa-check me-1"></i> Save &amp; Exit
    </a>
    @include('app.projects.datasets.ce-tabs._done-and-publish-button')
</div>
