<div>
    <!-- EditableMarkdown Component -->
    <livewire:editable-markdown save-event="markdownSaved" />

    <!-- Download Modal -->
    @if($showDownloadModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Download Markdown</h5>
                        <button type="button" class="btn-close" wire:click="toggleDownloadModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="downloadFilename">Filename</label>
                            <input type="text" class="form-control" id="downloadFilename" wire:model="downloadFilename" placeholder="Enter filename">
                            <small class="form-text text-muted">The .md extension will be added automatically if not included.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="toggleDownloadModal">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="download">Download</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Event Listener Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('markdownSaved', (event) => {
                @this.handleMarkdownSaved(event.content);
            });
        });
    </script>
</div>
