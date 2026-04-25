<div class="vr"></div>
<div class="px-3 py-2">
    <div class="text-muted fw-semibold" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.04em;">License</div>
    @if(!blank($dataset->license))
        <div>
            <a href="{{$licenseUrl()}}" target="_blank" class="text-decoration-none">
                <i class="fas fa-external-link-alt me-1 text-muted" style="font-size:.75rem;"></i>{{ $dataset->license }}
            </a>
        </div>
    @else
        <div class="text-muted">No license specified</div>
    @endif
</div>
