@once
    @auth
        @push('scripts')
            <script>
                (() => {
                    if (window.mcUploadStatusStore) {
                        return;
                    }

                    const storageKey = 'mc.uploadStatus.v1.user.{{auth()->id()}}';
                    const completedTtlMs = 5 * 60 * 1000;
                    const activeStaleMs = 5 * 60 * 1000;
                    const activeGraceMs = 15 * 1000;

                    const uploadCommands = new Set([
                        'upload_started',
                        'upload_progress',
                        'upload_completed',
                        'upload_failed',
                        'upload_cancelled',
                        'upload_snapshot',
                    ]);

                    let state = loadState();

                    function nowIso() {
                        return new Date().toISOString();
                    }

                    function loadState() {
                        try {
                            return JSON.parse(sessionStorage.getItem(storageKey)) || {projects: {}};
                        } catch (error) {
                            console.warn('Unable to load upload status state:', error);
                            return {projects: {}};
                        }
                    }

                    function saveState() {
                        try {
                            sessionStorage.setItem(storageKey, JSON.stringify(state));
                        } catch (error) {
                            console.warn('Unable to save upload status state:', error);
                        }
                    }

                    function projectBucket(projectId) {
                        const key = String(projectId);

                        state.projects[key] = state.projects[key] || {
                            files: {},
                            last_activity_at: null,
                        };

                        return state.projects[key];
                    }

                    function normalizeUpload(upload) {
                        return {
                            ...upload,
                            project_id: String(upload.project_id),
                            file_id: String(upload.file_id),
                            status: upload.status || 'uploading',
                            updated_at: upload.updated_at || nowIso(),
                        };
                    }

                    function isActiveStatus(status) {
                        return ['uploading', 'processing', 'received'].includes(status);
                    }

                    function isFailureStatus(status) {
                        return ['failed', 'cancelled', 'stalled'].includes(status);
                    }

                    function mergeUpload(upload) {
                        if (!upload || !upload.project_id || !upload.file_id) {
                            return;
                        }

                        const normalized = normalizeUpload(upload);
                        const bucket = projectBucket(normalized.project_id);
                        const existing = bucket.files[normalized.file_id] || {};

                        bucket.files[normalized.file_id] = {
                            ...existing,
                            ...normalized,
                            dismissed: false,
                        };

                        bucket.last_activity_at = normalized.updated_at;
                    }

                    function handleSnapshot(event) {
                        const activeUploads = Array.isArray(event.active_uploads) ? event.active_uploads : [];
                        const activeKeysByProject = {};

                        activeUploads.forEach((upload) => {
                            if (!upload.project_id || !upload.file_id) {
                                return;
                            }

                            const normalized = normalizeUpload(upload);
                            const projectId = String(normalized.project_id);

                            activeKeysByProject[projectId] = activeKeysByProject[projectId] || new Set();
                            activeKeysByProject[projectId].add(normalized.file_id);

                            mergeUpload({
                                ...normalized,
                                status: normalized.status || 'uploading',
                            });
                        });

                        Object.keys(activeKeysByProject).forEach((projectId) => {
                            const bucket = projectBucket(projectId);
                            const activeFileIds = activeKeysByProject[projectId];

                            Object.values(bucket.files).forEach((upload) => {
                                if (isActiveStatus(upload.status) && !activeFileIds.has(upload.file_id)) {
                                    const updatedAt = new Date(upload.updated_at || 0).getTime();

                                    if (Date.now() - updatedAt > activeGraceMs) {
                                        bucket.files[upload.file_id] = {
                                            ...upload,
                                            status: 'stalled',
                                            updated_at: nowIso(),
                                        };
                                    }
                                }
                            });
                        });
                    }

                    function prune() {
                        const now = Date.now();

                        Object.values(state.projects).forEach((bucket) => {
                            Object.keys(bucket.files).forEach((fileId) => {
                                const upload = bucket.files[fileId];
                                const updatedAt = new Date(upload.updated_at || 0).getTime();
                                const completedAt = new Date(upload.completed_at || upload.updated_at || 0).getTime();

                                if (isActiveStatus(upload.status) && updatedAt && now - updatedAt > activeStaleMs) {
                                    bucket.files[fileId] = {
                                        ...upload,
                                        status: 'stalled',
                                        updated_at: nowIso(),
                                    };

                                    return;
                                }

                                if (upload.status === 'completed' && completedAt && now - completedAt > completedTtlMs) {
                                    delete bucket.files[fileId];
                                }

                                if (upload.dismissed) {
                                    delete bucket.files[fileId];
                                }
                            });
                        });
                    }

                    function notify() {
                        prune();
                        saveState();

                        window.dispatchEvent(new CustomEvent('mc-upload-status-changed', {
                            detail: state,
                        }));
                    }

                    function handleEvent(event) {
                        if (!event || !uploadCommands.has(event.command)) {
                            return;
                        }

                        if (event.command === 'upload_snapshot') {
                            handleSnapshot(event);
                            notify();
                            return;
                        }

                        mergeUpload(event);
                        notify();
                    }

                    function uploadsForProject(projectId) {
                        prune();

                        const bucket = state.projects[String(projectId)];

                        if (!bucket) {
                            return [];
                        }

                        return Object.values(bucket.files)
                            .filter((upload) => !upload.dismissed)
                            .sort((a, b) => {
                                const aTime = new Date(a.updated_at || 0).getTime();
                                const bTime = new Date(b.updated_at || 0).getTime();

                                return bTime - aTime;
                            });
                    }

                    function summaryForProject(projectId) {
                        const uploads = uploadsForProject(projectId);
                        const activeCount = uploads.filter((upload) => isActiveStatus(upload.status)).length;
                        const failedCount = uploads.filter((upload) => upload.status === 'failed').length;
                        const cancelledCount = uploads.filter((upload) => upload.status === 'cancelled').length;
                        const stalledCount = uploads.filter((upload) => upload.status === 'stalled').length;
                        const recentCompletedCount = uploads.filter((upload) => upload.status === 'completed').length;
                        const attentionCount = failedCount + cancelledCount;

                        if (attentionCount > 0) {
                            return {
                                visible: true,
                                activeCount,
                                failedCount: attentionCount,
                                stalledCount,
                                recentCompletedCount,
                                icon: 'fa-exclamation-triangle text-danger',
                                badgeText: String(attentionCount),
                                badgeClass: 'text-bg-danger',
                                tooltip: `${attentionCount} upload${attentionCount === 1 ? '' : 's'} need attention. Click for details.`,
                            };
                        }

                        if (activeCount > 0) {
                            return {
                                visible: true,
                                activeCount,
                                failedCount: 0,
                                stalledCount,
                                recentCompletedCount,
                                icon: 'fa-cloud-upload-alt text-primary',
                                badgeText: String(activeCount),
                                badgeClass: 'text-bg-primary',
                                tooltip: `${activeCount} active upload${activeCount === 1 ? '' : 's'}. Click for details.`,
                            };
                        }

                        if (stalledCount > 0) {
                            return {
                                visible: true,
                                activeCount,
                                failedCount: 0,
                                stalledCount,
                                recentCompletedCount,
                                icon: 'fa-exclamation-circle text-warning',
                                badgeText: '!',
                                badgeClass: 'text-bg-warning',
                                tooltip: `${stalledCount} upload${stalledCount === 1 ? '' : 's'} may be stalled. Click for details.`,
                            };
                        }

                        if (recentCompletedCount > 0) {
                            return {
                                visible: true,
                                activeCount,
                                failedCount: 0,
                                stalledCount: 0,
                                recentCompletedCount,
                                icon: 'fa-check-circle text-success',
                                badgeText: '✓',
                                badgeClass: 'text-bg-success',
                                tooltip: `${recentCompletedCount} file${recentCompletedCount === 1 ? '' : 's'} uploaded recently. Click for details.`,
                            };
                        }

                        return {
                            visible: false,
                            activeCount: 0,
                            failedCount: 0,
                            stalledCount: 0,
                            recentCompletedCount: 0,
                            icon: 'fa-cloud-upload-alt',
                            badgeText: '',
                            badgeClass: 'text-bg-secondary',
                            tooltip: 'No upload activity.',
                        };
                    }

                    function clearCompleted(projectId) {
                        const bucket = state.projects[String(projectId)];

                        if (!bucket) {
                            return;
                        }

                        Object.keys(bucket.files).forEach((fileId) => {
                            if (bucket.files[fileId].status === 'completed') {
                                delete bucket.files[fileId];
                            }
                        });

                        notify();
                    }

                    function dismissFailures(projectId) {
                        const bucket = state.projects[String(projectId)];

                        if (!bucket) {
                            return;
                        }

                        Object.keys(bucket.files).forEach((fileId) => {
                            if (isFailureStatus(bucket.files[fileId].status)) {
                                delete bucket.files[fileId];
                            }
                        });

                        notify();
                    }

                    function percentFor(upload) {
                        const uploaded = Number(upload.bytes_uploaded ?? 0);
                        const total = Number(upload.total_bytes ?? 0);

                        if (!total || total <= 0) {
                            return null;
                        }

                        return Math.max(0, Math.min(100, Math.round((uploaded / total) * 100)));
                    }

                    function formatBytes(bytes) {
                        if (bytes === null || bytes === undefined || Number.isNaN(Number(bytes))) {
                            return 'Unknown';
                        }

                        const value = Number(bytes);

                        if (value < 1024) {
                            return `${value} B`;
                        }

                        const units = ['KB', 'MB', 'GB', 'TB'];
                        let size = value / 1024;
                        let unitIndex = 0;

                        while (size >= 1024 && unitIndex < units.length - 1) {
                            size = size / 1024;
                            unitIndex++;
                        }

                        return `${size.toFixed(size >= 10 ? 1 : 2)} ${units[unitIndex]}`;
                    }

                    function formatTimeAgo(value) {
                        if (!value) {
                            return '';
                        }

                        const timestamp = new Date(value).getTime();

                        if (Number.isNaN(timestamp)) {
                            return '';
                        }

                        const seconds = Math.max(0, Math.floor((Date.now() - timestamp) / 1000));

                        if (seconds < 10) {
                            return 'just now';
                        }

                        if (seconds < 60) {
                            return `${seconds}s ago`;
                        }

                        const minutes = Math.floor(seconds / 60);

                        if (minutes < 60) {
                            return `${minutes}m ago`;
                        }

                        const hours = Math.floor(minutes / 60);

                        return `${hours}h ago`;
                    }

                    prune();
                    saveState();

                    window.mcUploadStatusStore = {
                        handleEvent,
                        uploadsForProject,
                        summaryForProject,
                        clearCompleted,
                        dismissFailures,
                        isActiveStatus,
                        isFailureStatus,
                        percentFor,
                        formatBytes,
                        formatTimeAgo,
                    };

                    window.dispatchEvent(new CustomEvent('mc-upload-store-ready'));
                })();
            </script>
        @endpush
    @endauth
@endonce
