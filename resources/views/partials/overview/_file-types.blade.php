@if(isset($fileDescriptionTypes) && sizeof($fileDescriptionTypes) != 0)
    <div class="mb-3">
        <label for="file-types" class="fw-semibold text-muted small text-uppercase" style="letter-spacing:.03em;">
            <i class="fas fa-file-alt me-1"></i>File Types
            <span class="badge text-bg-secondary ms-1" style="font-size:.7rem;">{{ sizeof($fileDescriptionTypes) }}</span>
        </label>
        <div class="d-flex flex-wrap gap-1 mt-1">
            @foreach($fileDescriptionTypes as $type => $count)
                <span class="badge text-bg-light border text-dark" style="font-size:.78rem; font-weight:normal;">
                    {{ $type }}
                    <span class="badge text-bg-secondary ms-1" style="font-size:.7rem;">{{ $count }}</span>
                </span>
            @endforeach
        </div>
    </div>
@endif
