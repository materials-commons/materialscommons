<div>
    <div class="modal fade"
         id="global-search-modal"
         tabindex="-1"
         aria-labelledby="global-search-modal-title"
         aria-hidden="true"
         wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="global-search-modal-title">
                            <i class="fas fa-search me-2"></i>Search MaterialsCommons
                        </h5>
                        <div class="text-muted small">
                            Search published datasets, this project, or your projects.
                        </div>
                    </div>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <form onsubmit="submitGlobalSearchModal(event)"
                      data-search-url="{{ $searchUrl }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="global-search-query" class="form-label">Search query</label>
                            <input type="text"
                                   id="global-search-query"
                                   class="form-control form-control-lg"
                                   placeholder="{{$placeholder}}"
                                   wire:model.live.debounce.350ms="query"
                                   autocomplete="off">
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label for="global-search-scope" class="form-label">Scope</label>
                                <select id="global-search-scope"
                                        class="form-select"
                                        wire:model.live="scope">
                                    <option value="published">Published data</option>

                                    @if($projectId)
                                        <option value="project">This project: {{ $projectName }}</option>
                                    @endif

                                    @auth
                                        <option value="all-projects">All my projects</option>
                                    @endauth
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="global-search-type" class="form-label">Result type</label>
                                <select id="global-search-type"
                                        class="form-select"
                                        wire:model.live="type">
                                    <option value="all">All result types</option>
                                    <option value="files">Files</option>
                                    <option value="experiments">Experiments</option>
                                    <option value="entities">Samples</option>
                                    <option value="activities">Processes</option>
                                    <option value="datasets">Datasets</option>
                                    <option value="communities">Communities</option>
                                </select>
                            </div>
                        </div>

                        <div wire:loading.delay class="text-muted small mb-3">
                            <i class="fas fa-spinner fa-spin me-1"></i> Searching...
                        </div>

                        @if(blank($query))
                            <div class="alert alert-light border mb-0">
                                Start typing to preview results. Press Enter to open the full results page.
                            </div>
                        @elseif(mb_strlen(trim($query)) < 2)
                            <div class="alert alert-light border mb-0">
                                Type at least two characters to search.
                            </div>
                        @elseif($previewResults->isEmpty())
                            <div class="alert alert-light border mb-0">
                                No preview results found for <strong>{{ $query }}</strong>.
                            </div>
                        @else
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 text-muted">
                                    Preview results
                                </h6>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    View all results
                                    <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>

                            @include('partials.search._result-groups', [
                                'groups' => $previewResults,
                                'compact' => true,
                                'showViewTypeLinks' => false,
                            ])
                        @endif
                    </div>

                    <div class="modal-footer">
                        <div class="me-auto text-muted small">
                            Press <kbd style="color:black">Enter</kbd> to search. Press <kbd style="color:black">Esc</kbd> to close.
                        </div>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" @disabled(blank($query))>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function submitGlobalSearchModal(event) {
                event.preventDefault();

                const form = event.target;
                const searchUrl = form.dataset.searchUrl;

                if (!searchUrl) {
                    return;
                }

                const modalElement = document.getElementById('global-search-modal');

                if (modalElement && window.bootstrap) {
                    const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);
                    modal.hide();
                }

                document.querySelectorAll('.modal-backdrop').forEach((backdrop) => {
                    backdrop.remove();
                });

                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');

                window.location.href = searchUrl;
            }

            function openGlobalSearchFromShortcut() {
                const button = document.getElementById('global-search-button');

                if (button) {
                    button.click();
                }
            }

            document.addEventListener('shown.bs.modal', function (event) {
                if (event.target.id !== 'global-search-modal') {
                    return;
                }

                const input = document.getElementById('global-search-query');

                if (input) {
                    input.focus();
                    input.select();
                }
            });

            document.addEventListener('keydown', function (event) {
                const target = event.target;
                const isTyping = target && ['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName);

                if (isTyping) {
                    return;
                }

                if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                    event.preventDefault();
                    openGlobalSearchFromShortcut();
                }

                if (event.key === '/') {
                    event.preventDefault();
                    openGlobalSearchFromShortcut();
                }
            });
        </script>
    @endpush
</div>
