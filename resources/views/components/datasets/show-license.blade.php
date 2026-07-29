<div class="vr d-none d-md-block"></div>
<div class="px-3 py-2 bg-body-tertiary border rounded-3">
    <div class="text-muted fw-semibold small text-uppercase">License</div>
    @if(!blank($dataset->license))
        <div class="fw-semibold">
            <a href="{{$licenseUrl()}}" target="_blank" class="link-primary text-decoration-none">
                <i class="fas fa-external-link-alt me-1 text-muted"></i>{{ $dataset->license }}
            </a>
        </div>
    @else
        <div class="text-muted">No license specified</div>
    @endif
</div>
