@if(!blank($summary))
<div class="mb-4">
    <div class="fw-semibold text-muted text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
        <i class="fas fa-align-left me-1"></i>Description
    </div>
    <div class="border rounded bg-light px-3 py-2" style="font-size:.92rem; line-height:1.6;">
        {{ $summary }}
    </div>
</div>
@endif
