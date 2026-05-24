@if($dataset->zipfile_size > 0 || $dataset->globus_path_exists)
    @guest
        <div class="alert alert-info d-flex align-items-center gap-2 py-2 mt-2" role="alert">
            <i class="fas fa-lock fa-fw"></i>
            <span>
                To download this dataset please
                <a href="{{route('login')}}" class="alert-link">Login</a>
                or
                <a href="{{route('register')}}" class="alert-link">Register</a>.
            </span>
        </div>
    @else
        <div class="d-flex flex-wrap gap-2 mt-2 mb-1">
            @if($dataset->zipfile_size > 0)
                @if(is_null($dataset->published_at))
                    <a href="{{route('projects.datasets.download_zipfile', [$dataset->project_id, $dataset])}}"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-download me-1"></i>Download Zipfile
                        <span class="ms-1 opacity-75" style="font-size:.8rem;">({{formatBytes($dataset->zipfile_size)}})</span>
                    </a>
                @else
                    <a href="{{route('public.datasets.download_zipfile', [$dataset])}}"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-download me-1"></i>Download Zipfile
                        <span class="ms-1 opacity-75" style="font-size:.8rem;">({{formatBytes($dataset->zipfile_size)}})</span>
                    </a>
                @endif
            @endif

            @if($dataset->globus_path_exists)
                <a href="{{route('public.datasets.download_globus', [$dataset])}}"
                   class="btn btn-outline-secondary btn-sm" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>Download via Globus
                </a>
            @endif
        </div>
    @endguest
@else
    <div class="mt-2"></div>
@endif
