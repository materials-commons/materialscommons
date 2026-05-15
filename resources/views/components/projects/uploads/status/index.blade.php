@props([
    'project',
    'label' => 'CLI Uploads',
])

@php
    $projectId = is_object($project) ? $project->id : $project;
    $componentId = "mc-upload-status-{$projectId}";
    $offcanvasId = "mc-upload-status-details-{$projectId}";
@endphp

<x-projects.uploads.status.styles/>

<li class="nav-item d-none"
    id="{{$componentId}}"
    data-mc-upload-status
    data-project-id="{{$projectId}}">
    <button type="button"
            class="nav-link fs-11 ms-3 w-100 border-0 bg-transparent text-start d-flex align-items-center justify-content-between mc-upload-status-trigger"
            data-bs-toggle="offcanvas"
            data-bs-target="#{{$offcanvasId}}"
            aria-controls="{{$offcanvasId}}"
            title="Upload status">
        <span class="d-inline-flex align-items-center min-w-0">
            <i class="fas fa-fw fa-cloud-upload-alt me-2 mc-upload-status-icon"></i>
            <span class="mc-upload-status-label text-truncate">{{$label}}</span>
        </span>

        <span class="badge rounded-pill ms-2 mc-upload-status-badge d-none"></span>
    </button>
</li>

<x-projects.uploads.status.details :project="$project"
                                   :offcanvas-id="$offcanvasId"/>

<x-projects.uploads.status.templates/>

@push('scripts')
    <script>
        (() => {
            const projectId = String(@json($projectId));
            const componentId = @json($componentId);
            const offcanvasId = @json($offcanvasId);

            const root = document.getElementById(componentId);
            const offcanvas = document.getElementById(offcanvasId);

            if (!root || !offcanvas) {
                return;
            }

            const badge = root.querySelector('.mc-upload-status-badge');
            const icon = root.querySelector('.mc-upload-status-icon');
            const trigger = root.querySelector('.mc-upload-status-trigger');

            const summaryText = offcanvas.querySelector('[data-mc-upload-summary]');
            const emptyState = offcanvas.querySelector('[data-mc-upload-empty]');
            const clearButton = offcanvas.querySelector('[data-mc-upload-clear]');
            const dismissFailuresButton = offcanvas.querySelector('[data-mc-upload-dismiss-failures]');

            const activeSection = offcanvas.querySelector('[data-mc-upload-active-section]');
            const failedSection = offcanvas.querySelector('[data-mc-upload-failed-section]');
            const recentSection = offcanvas.querySelector('[data-mc-upload-recent-section]');

            const activeList = offcanvas.querySelector('[data-mc-upload-active-list]');
            const failedList = offcanvas.querySelector('[data-mc-upload-failed-list]');
            const recentList = offcanvas.querySelector('[data-mc-upload-recent-list]');

            const rowTemplate = offcanvas.querySelector('[data-mc-upload-row-template]') ||
                document.querySelector('[data-mc-upload-row-template]');

            function statusLabel(status) {
                return String(status || 'uploading').replaceAll('_', ' ');
            }

            function statusPresentation(upload) {
                const status = upload.status || 'uploading';
                const store = window.mcUploadStatusStore;

                if (store?.isActiveStatus(status)) {
                    return {
                        wrapperClass: 'text-primary',
                        iconClass: 'fa-spinner fa-spin',
                    };
                }

                if (status === 'completed') {
                    return {
                        wrapperClass: 'text-success',
                        iconClass: 'fa-check-circle',
                    };
                }

                if (status === 'cancelled') {
                    return {
                        wrapperClass: 'text-warning',
                        iconClass: 'fa-ban',
                    };
                }

                if (status === 'failed' || status === 'stalled') {
                    return {
                        wrapperClass: 'text-danger',
                        iconClass: 'fa-exclamation-circle',
                    };
                }

                return {
                    wrapperClass: 'text-muted',
                    iconClass: 'fa-clock',
                };
            }

            function filenameFor(upload) {
                return upload.filename || upload.path || 'Unknown file';
            }

            function renderUploadRow(upload) {
                const store = window.mcUploadStatusStore;

                if (!rowTemplate || !store) {
                    return null;
                }

                const row = rowTemplate.content.firstElementChild.cloneNode(true);
                const percent = store.percentFor(upload);
                const uploaded = store.formatBytes(upload.bytes_uploaded ?? 0);
                const total = upload.total_bytes ? store.formatBytes(upload.total_bytes) : 'Unknown';
                const timeValue = upload.completed_at || upload.failed_at || upload.updated_at || upload.started_at;
                const presentation = statusPresentation(upload);

                row.querySelector('[data-mc-upload-filename]').textContent = filenameFor(upload);
                row.querySelector('[data-mc-upload-filename]').setAttribute('title', filenameFor(upload));

                const pathEl = row.querySelector('[data-mc-upload-path]');
                if (upload.path) {
                    pathEl.textContent = upload.path;
                    pathEl.setAttribute('title', upload.path);
                    pathEl.classList.remove('d-none');
                }

                const statusWrapper = row.querySelector('[data-mc-upload-status-wrapper]');
                const statusIcon = row.querySelector('[data-mc-upload-status-icon]');
                const statusText = row.querySelector('[data-mc-upload-status]');

                statusWrapper.classList.add(presentation.wrapperClass);
                statusIcon.className = `fas me-1 ${presentation.iconClass}`;
                statusText.textContent = statusLabel(upload.status);

                const progress = row.querySelector('[data-mc-upload-progress]');
                const progressBar = row.querySelector('[data-mc-upload-progress-bar]');
                const size = row.querySelector('[data-mc-upload-size]');
                const time = row.querySelector('[data-mc-upload-time]');

                if (percent === null) {
                    size.textContent = uploaded;
                } else {
                    progress.classList.remove('d-none');
                    progressBar.style.width = `${percent}%`;
                    progressBar.setAttribute('aria-valuenow', String(percent));

                    if (store.isFailureStatus(upload.status)) {
                        progressBar.classList.add('bg-danger');
                    }

                    size.textContent = `${percent}% · ${uploaded} / ${total}`;
                }

                time.textContent = store.formatTimeAgo(timeValue);

                const error = row.querySelector('[data-mc-upload-error]');
                if (upload.error) {
                    error.textContent = upload.error;
                    error.classList.remove('d-none');
                }

                return row;
            }

            function replaceRows(container, uploads) {
                container.replaceChildren();

                uploads.forEach((upload) => {
                    const row = renderUploadRow(upload);

                    if (row) {
                        container.appendChild(row);
                    }
                });
            }

            function render() {
                const store = window.mcUploadStatusStore;

                if (!store) {
                    return;
                }

                const summary = store.summaryForProject(projectId);
                const uploads = store.uploadsForProject(projectId);

                root.classList.toggle('d-none', !summary.visible);

                if (!summary.visible) {
                    return;
                }

                icon.className = `fas fa-fw me-2 mc-upload-status-icon ${summary.icon}`;

                badge.textContent = summary.badgeText;
                badge.className = `badge rounded-pill ms-2 mc-upload-status-badge ${summary.badgeClass}`;
                badge.classList.toggle('d-none', !summary.badgeText);

                trigger.setAttribute('title', summary.tooltip);
                summaryText.textContent = summary.tooltip;

                const activeUploads = uploads.filter((upload) => store.isActiveStatus(upload.status));
                const failedUploads = uploads.filter((upload) => store.isFailureStatus(upload.status));
                const recentUploads = uploads.filter((upload) => upload.status === 'completed');

                replaceRows(activeList, activeUploads);
                replaceRows(failedList, failedUploads);
                replaceRows(recentList, recentUploads);

                activeSection.classList.toggle('d-none', activeUploads.length === 0);
                failedSection.classList.toggle('d-none', failedUploads.length === 0);
                recentSection.classList.toggle('d-none', recentUploads.length === 0);
                emptyState.classList.toggle('d-none', uploads.length > 0);
            }

            clearButton?.addEventListener('click', () => {
                window.mcUploadStatusStore?.clearCompleted(projectId);
                render();
            });

            dismissFailuresButton?.addEventListener('click', () => {
                window.mcUploadStatusStore?.dismissFailures(projectId);
                render();
            });

            window.addEventListener('mc-upload-status-changed', render);
            window.addEventListener('mc-upload-store-ready', render);

            document.addEventListener('DOMContentLoaded', render);

            render();
        })();
    </script>
@endpush
