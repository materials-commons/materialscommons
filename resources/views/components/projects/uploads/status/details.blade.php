@props([
    'project',
    'offcanvasId',
])

@php
    $projectName = is_object($project) ? $project->name : 'Project';
@endphp

<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="{{$offcanvasId}}"
     aria-labelledby="{{$offcanvasId}}-label">
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="offcanvas-title mb-0" id="{{$offcanvasId}}-label">
                <i class="fas fa-cloud-upload-alt me-2"></i>CLI Uploads
            </h5>
            <div class="text-muted small text-truncate" title="{{$projectName}}">
                {{$projectName}}
            </div>
        </div>

        <button type="button"
                class="btn-close text-reset"
                data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <div class="alert alert-light border py-2 mb-3 small"
             data-mc-upload-summary>
            No upload activity.
        </div>

        <div class="d-flex gap-2 mb-3">
            <button type="button"
                    class="btn btn-outline-secondary btn-sm"
                    data-mc-upload-clear>
                <i class="fas fa-broom me-1"></i>Clear completed
            </button>

            <button type="button"
                    class="btn btn-outline-danger btn-sm"
                    data-mc-upload-dismiss-failures>
                <i class="fas fa-times-circle me-1"></i>Dismiss failures
            </button>
        </div>

        <div data-mc-upload-empty class="text-center text-muted py-5">
            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
            <div class="fw-semibold">No upload activity</div>
            <div class="small">CLI uploads for this project will appear here.</div>
        </div>

        <section data-mc-upload-active-section class="d-none mb-4">
            <div class="d-flex align-items-center mb-2">
                <div class="fw-semibold text-uppercase text-muted mc-upload-section-heading">
                    Active
                </div>
                <hr class="flex-grow-1 ms-2 my-0">
            </div>

            <div data-mc-upload-active-list></div>
        </section>

        <section data-mc-upload-failed-section class="d-none mb-4">
            <div class="d-flex align-items-center mb-2">
                <div class="fw-semibold text-uppercase text-danger mc-upload-section-heading">
                    Needs attention
                </div>
                <hr class="flex-grow-1 ms-2 my-0">
            </div>

            <div data-mc-upload-failed-list></div>
        </section>

        <section data-mc-upload-recent-section class="d-none">
            <div class="d-flex align-items-center mb-2">
                <div class="fw-semibold text-uppercase text-muted mc-upload-section-heading">
                    Recently uploaded
                </div>
                <hr class="flex-grow-1 ms-2 my-0">
            </div>

            <div data-mc-upload-recent-list></div>
        </section>
    </div>
</div>
