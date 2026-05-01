@if(!blank($description))
<div class="mb-4">
    <div class="fw-semibold text-muted text-uppercase mb-2" style="font-size:.72rem; letter-spacing:.04em;">
        <i class="fas fa-align-left me-1"></i>Description
    </div>
    <x-markdown class="border rounded bg-light px-3 pt-2 pb-1">{!!$description!!}</x-markdown>
</div>
@endif
