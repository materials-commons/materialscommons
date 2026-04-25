@if(!blank($authors))
    <div class="mb-3 mt-2">
        <label class="fw-semibold text-muted small text-uppercase" style="letter-spacing:.03em;">
            <i class="fas fa-users me-1"></i>Authors
        </label>
        <div class="d-flex flex-wrap gap-2 mt-1">
            @foreach($authors as $author)
                <span class="badge text-bg-light border text-dark px-2 py-1" style="font-size:.82rem; font-weight:normal;">
                    <i class="fas fa-user-circle me-1 text-muted"></i>{{ $author['name'] }}
                </span>
            @endforeach
        </div>
    </div>
@endif
