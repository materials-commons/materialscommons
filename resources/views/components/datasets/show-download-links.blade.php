@if($dataset->zipfile_size > 0 || $dataset->globus_path_exists)
    @guest
        <div class="alert alert-info d-flex align-items-center gap-2 py-2 mt-2 mb-0" role="alert"
             style="font-size:.82rem; border-radius:.65rem;">
            <i class="fas fa-lock fa-fw"></i>
            <span>
                To download this dataset please
                <a href="{{ route('login') }}" class="alert-link">Login</a>
                or
                <a href="{{ route('register') }}" class="alert-link">Register</a>.
            </span>
        </div>
    @else
        <div class="d-grid gap-2 mt-2 mb-1">
            @if($dataset->zipfile_size > 0)
                @if(is_null($dataset->published_at))
                    <a href="{{ route('projects.datasets.download_zipfile', [$dataset->project_id, $dataset]) }}"
                       class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-download me-1"></i>
                        <span>Download Zipfile</span>
                        <span class="ms-1 opacity-75" style="font-size:.8rem;">
                            ({{ formatBytes($dataset->zipfile_size) }})
                        </span>
                    </a>
                @else
                    <a href="{{ route('public.datasets.download_zipfile', [$dataset]) }}"
                       class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-download me-1"></i>
                        <span>Download Zipfile</span>
                        <span class="ms-1 opacity-75" style="font-size:.8rem;">
                            ({{ formatBytes($dataset->zipfile_size) }})
                        </span>
                    </a>
                @endif
            @endif

            @if($dataset->globus_path_exists)
                <a href="{{ route('public.datasets.download_globus', [$dataset]) }}"
                   class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center"
                   target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>
                    <span>Download via Globus</span>
                </a>
            @endif
        </div>
    @endguest
@else
    <div class="text-muted mt-2" style="font-size:.78rem;">
        <i class="fas fa-info-circle me-1"></i>
        Download files are not available yet.
    </div>
@endif
