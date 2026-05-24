<template data-mc-upload-row-template>
    <div class="border rounded p-2 mb-2 bg-white" data-mc-upload-row>
        <div class="d-flex align-items-start justify-content-between gap-2">
            <div class="min-w-0">
                <div class="fw-semibold text-truncate"
                     data-mc-upload-filename></div>

                <div class="text-muted text-truncate mc-upload-small d-none"
                     data-mc-upload-path></div>
            </div>

            <div class="text-nowrap mc-upload-small"
                 data-mc-upload-status-wrapper>
                <i class="fas me-1" data-mc-upload-status-icon></i>
                <span data-mc-upload-status></span>
            </div>
        </div>

        <div class="mt-2">
            <div class="progress mc-upload-progress d-none"
                 data-mc-upload-progress>
                <div class="progress-bar"
                     data-mc-upload-progress-bar
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>

            <div class="d-flex justify-content-between text-muted mt-1 mc-upload-small">
                <span data-mc-upload-size></span>
                <span data-mc-upload-time></span>
            </div>
        </div>

        <div class="alert alert-danger py-1 px-2 mt-2 mb-0 mc-upload-small d-none"
             data-mc-upload-error></div>
    </div>
</template>
