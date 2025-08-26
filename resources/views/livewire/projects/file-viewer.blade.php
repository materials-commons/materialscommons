<div class="h-100">
    {{$selectedFile}}
    @if(false)
        @if(!blank($selectedFile))
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa-fw fas fa-{{ $selectedFile->isDir() ? 'folder' : 'file' }} mr-2"></i>
                        {{ $selectedFile->name }}
                    </h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="clearSelection">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="card-body overflow-auto">
                    @if($selectedFile->isDir())
                        <div class="text-center text-muted">
                            <i class="fas fa-folder fa-3x mb-3"></i>
                            <p>Directory: {{ $selectedFile->name }}</p>
                            <p class="small">Click to navigate into this directory</p>
                        </div>
                    @elseif($selectedFile->mime_type == 'url')
                        <div class="text-center">
                            <i class="fas fa-link fa-3x mb-3 text-primary"></i>
                            <p><strong>URL:</strong> {{ $selectedFile->name }}</p>
                            <a href="{{ $selectedFile->url }}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt mr-2"></i>Open Link
                            </a>
                        </div>
                    @elseif($selectedFile->isImage())
                        <div class="text-center">
                            <img src="{{ route('projects.files.display', [$project, $selectedFile]) }}"
                                 class="img-fluid" alt="{{ $selectedFile->name }}">
                        </div>
                        <hr>
                        <div class="file-details">
                            <p><strong>Size:</strong> {{ $selectedFile->toHumanBytes() }}</p>
                            <p>
                                <strong>Type:</strong> {{ $selectedFile->mimeTypeToDescriptionForDisplay($selectedFile) }}
                            </p>
                            <p><strong>Last Updated:</strong> {{ $selectedFile->created_at->diffForHumans() }}</p>
                        </div>
                    @else
                        <div class="text-center text-muted mb-3">
                            <i class="fas fa-file fa-3x mb-3"></i>
                            <p><strong>{{ $selectedFile->name }}</strong></p>
                        </div>
                        <div class="file-details">
                            <p><strong>Size:</strong> {{ $selectedFile->toHumanBytes() }}</p>
                            <p>
                                <strong>Type:</strong> {{ $selectedFile->mimeTypeToDescriptionForDisplay($selectedFile) }}
                            </p>
                            <p><strong>Last Updated:</strong> {{ $selectedFile->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('projects.files.show', [$project, $selectedFile]) }}"
                               class="btn btn-primary">
                                <i class="fas fa-eye mr-2"></i>View File Details
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-center text-muted">
                    <div class="text-center">
                        <i class="fas fa-mouse-pointer fa-3x mb-3"></i>
                        <h5>Select a file to preview</h5>
                        <p>Click on any file or folder in the directory listing to view it here.</p>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
